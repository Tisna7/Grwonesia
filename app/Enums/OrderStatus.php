<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Paid = 'paid';
    case Shipped = 'shipped';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu',
            self::Paid => 'Dibayar',
            self::Shipped => 'Dikirim',
            self::Completed => 'Selesai',
            self::Cancelled => 'Dibatalkan',
        };
    }

    /**
     * Status yang dihitung sebagai omzet (bukan pending/cancelled).
     *
     * @return array<string>
     */
    public static function revenueStatuses(): array
    {
        return [self::Paid->value, self::Shipped->value, self::Completed->value];
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Pending => 'bg-amber-500/15 text-amber-400 border-amber-500/40',
            self::Paid => 'bg-primary-500/15 text-primary-300 border-primary-500/40',
            self::Shipped => 'bg-indigo-500/15 text-indigo-300 border-indigo-500/40',
            self::Completed => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/40',
            self::Cancelled => 'bg-rose-500/15 text-rose-400 border-rose-500/40',
        };
    }
}
