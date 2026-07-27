# Grownesia WA Gateway (Baileys)

Service Node.js terpisah yang menjembatani Laravel ↔ WhatsApp lewat [Baileys](https://github.com/WhiskeySockets/Baileys) (WhatsApp Web protocol, tidak resmi).

> ⚠️ **Risiko banned**: Baileys bukan API resmi WhatsApp. Gunakan **nomor khusus bisnis** (bukan nomor pribadi), hindari broadcast masif ke nomor yang belum pernah chat, dan biarkan `WA_SEND_DELAY_MS` aktif.

## Menjalankan

```bash
cd wa-gateway
npm install
npm start
```

Lalu buka **http://localhost:3010/qr** dan scan dengan WhatsApp di HP (Menu → Perangkat Tertaut → Tautkan Perangkat). Sesi tersimpan di folder `auth/` — scan cukup sekali.

## Endpoint

| Method | Path | Auth | Deskripsi |
|---|---|---|---|
| GET | `/status` | — | Status koneksi (`connected: true/false`) |
| GET | `/qr` | — | Halaman QR untuk login |
| POST | `/send` | Bearer token | Kirim pesan: `{ "to": "628xx", "message": "..." }` → `{ "id": "..." }` |

## Konfigurasi

Salin `.env.example` ke `.env`. `WA_GATEWAY_TOKEN` **harus sama** dengan yang ada di `.env` Laravel.

Di sisi Laravel (`.env` root):

```
WA_DRIVER=baileys
WA_GATEWAY_URL=http://127.0.0.1:3010
WA_GATEWAY_TOKEN=<token yang sama>
```

Kalau gateway mati/belum scan QR, Laravel tetap aman: pesan tercatat berstatus `failed` (bukan error 500), dan bisa dikembalikan ke mode simulasi kapan pun dengan `WA_DRIVER=log`.
