<?php

namespace App\Enums;

enum UserRole: string
{
    case Consumer = 'user';
    case Business = 'business';
    case Government = 'government';
    case Admin = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::Consumer => 'Pembeli',
            self::Business => 'Akun Bisnis',
            self::Government => 'Pemerintah',
            self::Admin => 'Super Admin',
        };
    }
}
