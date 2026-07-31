import 'dotenv/config';
import express from 'express';
import pino from 'pino';
import QRCode from 'qrcode';
import qrcodeTerminal from 'qrcode-terminal';
import makeWASocket, {
    useMultiFileAuthState,
    DisconnectReason,
    fetchLatestBaileysVersion,
    downloadMediaMessage,
} from '@whiskeysockets/baileys';
import { rmSync, writeFileSync, mkdirSync } from 'node:fs';
import { join } from 'node:path';
import { setTimeout as sleep } from 'node:timers/promises';


const PORT = Number(process.env.WA_PORT ?? 3010);
const TOKEN = process.env.WA_GATEWAY_TOKEN ?? '';
const AUTH_DIR = new URL('./auth', import.meta.url).pathname.replace(/^\/([A-Za-z]:)/, '$1');
// Jeda antar pesan broadcast (ms) — mengurangi risiko banned oleh WhatsApp
const SEND_DELAY_MS = Number(process.env.WA_SEND_DELAY_MS ?? 1500);

const logger = pino({ level: process.env.WA_LOG_LEVEL ?? 'warn' });

let sock = null;
let connectionState = 'connecting'; // connecting | open | close | logged_out
let lastQR = null;
let lastSentAt = 0;

async function startSocket() {
    const { state, saveCreds } = await useMultiFileAuthState(AUTH_DIR);
    const { version } = await fetchLatestBaileysVersion();

    sock = makeWASocket({
        version,
        auth: state,
        logger,
        // QR ditangani manual lewat connection.update
        printQRInTerminal: false,
        generateHighQualityLinkPreview: false,
    });

    sock.ev.on('creds.update', saveCreds);

    sock.ev.on('connection.update', (update) => {
        const { connection, lastDisconnect, qr } = update;

        if (qr) {
            lastQR = qr;
            connectionState = 'connecting';
            console.log('\n📱 Scan QR berikut dengan WhatsApp (Perangkat Tertaut):\n');
            qrcodeTerminal.generate(qr, { small: true });
            console.log(`   Atau buka http://localhost:${PORT}/qr di browser.\n`);
        }

        if (connection === 'open') {
            lastQR = null;
            connectionState = 'open';
            console.log(`✅ WhatsApp terhubung sebagai ${sock.user?.id ?? 'unknown'}`);
        }

        if (connection === 'close') {
            const statusCode = lastDisconnect?.error?.output?.statusCode;

            if (statusCode === DisconnectReason.loggedOut) {
                connectionState = 'logged_out';
                console.log('🚪 Sesi logout dari HP. Menghapus kredensial — scan ulang QR untuk masuk.');
                try {
                    rmSync(AUTH_DIR, { recursive: true, force: true });
                } catch {}
                startSocket();
            } else {
                connectionState = 'close';
                console.log('🔌 Koneksi terputus, mencoba sambung ulang…');
                startSocket();
            }
        }
    });

    const LARAVEL_URL = process.env.LARAVEL_URL ?? 'http://localhost:8000';

    sock.ev.on('messages.upsert', async (upsert) => {
        const { messages, type } = upsert;
        if (type !== 'notify') return;

        for (const msg of messages) {
            if (msg.key.fromMe || !msg.message) continue;

            const from = msg.key.remoteJid;
            if (!from || from.endsWith('@g.us') || from.endsWith('@newsletter')) continue;

            const messageId = msg.key.id;
            let text = '';
            let mediaPath = null;
            let typeMsg = 'text';

            if (msg.message.conversation) {
                text = msg.message.conversation;
            } else if (msg.message.extendedTextMessage) {
                text = msg.message.extendedTextMessage.text;
            } else if (msg.message.imageMessage) {
                text = msg.message.imageMessage.caption ?? '';
                typeMsg = 'image';

                try {
                    console.log(`Downloading media for message ${messageId}...`);
                    const buffer = await downloadMediaMessage(
                        msg,
                        'buffer',
                        {},
                        {
                            logger,
                            reuploadRequest: sock.updateMediaMessage,
                        }
                    );

                    const tempDir = join(AUTH_DIR, '../temp');
                    mkdirSync(tempDir, { recursive: true });
                    const tempFilename = `temp_${messageId}.jpg`;
                    mediaPath = join(tempDir, tempFilename);
                    writeFileSync(mediaPath, buffer);
                    console.log(`Media saved to ${mediaPath}`);
                } catch (err) {
                    console.error('Gagal mendownload media:', err.message);
                }
            }

            if (!text && !mediaPath) continue;

            try {
                console.log(`Sending webhook to Laravel: ${from} -> ${text}`);
                const response = await fetch(`${LARAVEL_URL}/whatsapp/webhook`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${TOKEN}`,
                    },
                    body: JSON.stringify({
                        from: from,
                        type: typeMsg,
                        body: text,
                        tempPath: mediaPath,
                    }),
                });

                if (!response.ok) {
                    const errText = await response.text();
                    console.error(`Laravel Webhook error (${response.status}):`, errText);
                }
            } catch (err) {
                console.error('Gagal mengirim webhook ke Laravel:', err.message);
            }
        }
    });
}

// ---------- HTTP API ----------

const app = express();
app.use(express.json());

// Autentikasi Bearer token (kecuali /qr & /status agar mudah dicek dari browser lokal)
app.use((req, res, next) => {
    if (['/qr', '/status'].includes(req.path)) return next();
    if (!TOKEN) return next(); // token kosong = tanpa auth (hanya untuk dev lokal)

    const header = req.headers.authorization ?? '';
    if (header === `Bearer ${TOKEN}`) return next();

    return res.status(401).json({ error: 'Unauthorized' });
});

app.get('/status', (_req, res) => {
    res.json({
        state: connectionState,
        connected: connectionState === 'open',
        user: sock?.user?.id ?? null,
        needs_qr: lastQR !== null,
    });
});

app.get('/qr', async (_req, res) => {
    if (connectionState === 'open') {
        return res
            .type('html')
            .send('<body style="font-family:sans-serif;background:#0B0F19;color:#eee;display:grid;place-items:center;height:100vh"><div>✅ Sudah terhubung sebagai <b>'
                + (sock?.user?.id ?? '') + '</b></div></body>');
    }

    if (!lastQR) {
        return res
            .type('html')
            .send('<body style="font-family:sans-serif;background:#0B0F19;color:#eee;display:grid;place-items:center;height:100vh"><div>⏳ QR belum tersedia — tunggu sebentar lalu refresh.</div></body>');
    }

    const dataUrl = await QRCode.toDataURL(lastQR, { width: 320, margin: 2 });

    res.type('html').send(
        `<body style="font-family:sans-serif;background:#0B0F19;color:#eee;display:grid;place-items:center;height:100vh">
            <div style="text-align:center">
                <img src="${dataUrl}" style="border-radius:12px" alt="QR">
                <p>Scan dengan WhatsApp → Perangkat Tertaut</p>
                <p style="color:#888;font-size:12px">Halaman auto-refresh tiap 20 detik</p>
            </div>
            <script>setTimeout(() => location.reload(), 20000)</script>
        </body>`,
    );
});

app.post('/send', async (req, res) => {
    const { to, message } = req.body ?? {};

    if (!to || !message) {
        return res.status(422).json({ error: 'Field "to" dan "message" wajib diisi.' });
    }

    if (connectionState !== 'open' || !sock) {
        return res.status(503).json({ error: 'WhatsApp belum terhubung. Scan QR dulu di /qr.' });
    }

    let jid;
    if (String(to).includes('@')) {
        jid = to;
    } else {
        const number = String(to).replace(/\D/g, '');
        if (number.length < 8) {
            return res.status(422).json({ error: 'Nomor tujuan tidak valid.' });
        }
        jid = `${number}@s.whatsapp.net`;
    }

    try {
        let targetJid = jid;
        // Pastikan nomor terdaftar di WhatsApp (onWhatsApp hanya bekerja untuk nomor telepon, dilewati untuk LID)
        if (!jid.endsWith('@lid')) {
            const [check] = await sock.onWhatsApp(jid);
            if (!check?.exists) {
                return res.status(404).json({ error: `Nomor ${jid} tidak terdaftar di WhatsApp.` });
            }
            targetJid = check.jid ?? jid;
        }

        // Throttle sederhana antar pengiriman (anti-spam / anti-banned)
        const wait = lastSentAt + SEND_DELAY_MS - Date.now();
        if (wait > 0) await sleep(wait);
        lastSentAt = Date.now();

        const result = await sock.sendMessage(targetJid, { text: String(message) });

        return res.json({ id: result?.key?.id ?? null, to: targetJid });
    } catch (err) {
        console.error('Gagal kirim:', err?.message ?? err);

        return res.status(500).json({ error: 'Gagal mengirim pesan: ' + (err?.message ?? 'unknown') });
    }
});

app.get('/resolve', async (req, res) => {
    const { phone } = req.query ?? {};

    if (!phone) {
        return res.status(422).json({ error: 'Parameter "phone" wajib diisi.' });
    }

    if (connectionState !== 'open' || !sock) {
        return res.status(503).json({ error: 'WhatsApp belum terhubung.' });
    }

    const number = String(phone).replace(/\D/g, '');
    if (number.length < 8) {
        return res.status(422).json({ error: 'Nomor tidak valid.' });
    }

    try {
        const jid = `${number}@s.whatsapp.net`;
        const [check] = await sock.onWhatsApp(jid);
        console.log('RESOLVE CHECK:', check);
        if (check?.exists) {
            return res.json({ exists: true, jid: check.lid ?? check.jid });
        }
        return res.json({ exists: false, jid: null });
    } catch (err) {
        console.error('Gagal resolve:', err?.message ?? err);
        return res.status(500).json({ error: 'Gagal resolve nomor: ' + (err?.message ?? 'unknown') });
    }
});

app.listen(PORT, () => {
    console.log(`🚀 Grownesia WA Gateway berjalan di http://localhost:${PORT}`);
    console.log(`   Status : http://localhost:${PORT}/status`);
    console.log(`   QR     : http://localhost:${PORT}/qr`);
    startSocket();
});
