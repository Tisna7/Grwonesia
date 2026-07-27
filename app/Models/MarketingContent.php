<?php

namespace App\Models;

use App\Enums\MarketingContentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketingContent extends Model
{
    protected $fillable = [
        'business_id',
        'product_id',
        'type',
        'brief',
        'content',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'type' => MarketingContentType::class,
            'meta' => 'array',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
