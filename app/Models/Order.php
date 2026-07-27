<?php

namespace App\Models;

use App\Enums\OrderChannel;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'customer_id',
        'order_number',
        'status',
        'channel',
        'total',
        'total_cost',
        'notes',
        'ordered_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'channel' => OrderChannel::class,
            'total' => 'decimal:2',
            'total_cost' => 'decimal:2',
            'ordered_at' => 'datetime',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateOrderNumber(): string
    {
        return 'GRW-'.now()->format('ymd').'-'.strtoupper(substr(uniqid(), -5));
    }
}
