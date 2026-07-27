<?php

namespace App\Services\WhatsApp;

interface WhatsAppGateway
{
    public function send(string $toNumber, string $body): WaSendResult;

    /**
     * True jika gateway benar-benar mengirim pesan (bukan mock/log).
     */
    public function isLive(): bool;
}
