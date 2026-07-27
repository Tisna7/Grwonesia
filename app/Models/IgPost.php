<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IgPost extends Model
{
    protected $fillable = [
        'business_id',
        'product_id',
        'image_url',
        'caption',
        'status',
        'ig_media_id',
        'error',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
