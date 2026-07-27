<?php

namespace App\Services\WhatsApp;

use Illuminate\Support\Facades\Log;

class LogWhatsAppGateway implements WhatsAppGateway
{
    public function send(string $toNumber, string $body): WaSendResult
    {
        Log::info('[WA MOCK] Pesan WhatsApp (tidak benar-benar terkirim)', [
            'to' => $toNumber,
            'body' => $body,
        ]);

        return new WaSendResult(success: true, providerMessageId: 'mock-'.uniqid());
    }

    public function isLive(): bool
    {
        return false;
    }
}
