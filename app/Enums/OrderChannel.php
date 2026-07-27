<?php

namespace App\Enums;

enum OrderChannel: string
{
    case Manual = 'manual';
    case Whatsapp = 'whatsapp';
    case Marketplace = 'marketplace';

    public function label(): string
    {
        return match ($this) {
            self::Manual => 'Manual',
            self::Whatsapp => 'WhatsApp',
            self::Marketplace => 'Marketplace',
        };
    }
}
