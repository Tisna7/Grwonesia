<?php

namespace App\Services\WhatsApp;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Gateway untuk API WhatsApp berbasis Baileys buatan sendiri.
 * Ekspektasi endpoint: POST {WA_GATEWAY_URL}/send  body: { "to": "...", "message": "..." }
 * Aktifkan via env: WA_DRIVER=baileys, WA_GATEWAY_URL, WA_GATEWAY_TOKEN (opsional).
 */
class BaileysWhatsAppGateway implements WhatsAppGateway
{
    public function send(string $toNumber, string $body): WaSendResult
    {
        try {
            $request = Http::timeout(15);

            if (filled(config('services.whatsapp.token'))) {
                $request = $request->withToken(config('services.whatsapp.token'));
            }

            $response = $request->post(rtrim((string) config('services.whatsapp.url'), '/').'/send', [
                'to' => $toNumber,
                'message' => $body,
            ]);

            if ($response->failed()) {
                Log::warning('BaileysWhatsAppGateway: kirim gagal', [
                    'status' => $response->status(),
                    'body' => mb_substr($response->body(), 0, 500),
                ]);

                return new WaSendResult(success: false, raw: ['status' => $response->status()]);
            }

            $json = $response->json() ?? [];

            return new WaSendResult(
                success: true,
                providerMessageId: $json['id'] ?? $json['messageId'] ?? null,
                raw: $json,
            );
        } catch (Throwable $e) {
            Log::warning('BaileysWhatsAppGateway: exception', ['message' => $e->getMessage()]);

            return new WaSendResult(success: false, raw: ['error' => $e->getMessage()]);
        }
    }

    public function isLive(): bool
    {
        return true;
    }
}
