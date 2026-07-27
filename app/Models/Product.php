<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'name',
        'slug',
        'sku',
        'category',
        'description',
        'price',
        'cost_price',
        'stock',
        'min_stock',
        'photo_path',
        'status',
        'ai_photo_analysis',
        'ai_seo_suggestions',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'ai_photo_analysis' => 'array',
            'ai_seo_suggestions' => 'array',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function marginPercent(): float
    {
        if ((float) $this->price <= 0) {
            return 0.0;
        }

        return round(((float) $this->price - (float) $this->cost_price) / (float) $this->price * 100, 1);
    }

    public function isLowStock(): bool
    {
        return $this->stock <= $this->min_stock;
    }
}
