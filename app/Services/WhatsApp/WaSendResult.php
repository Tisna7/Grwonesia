<?php

namespace App\Services\WhatsApp;

readonly class WaSendResult
{
    public function __construct(
        public bool $success,
        public ?string $providerMessageId = null,
        public array $raw = [],
    ) {}
}
