<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiInsight extends Model
{
    protected $fillable = [
        'business_id',
        'type',
        'date',
        'payload',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'payload' => 'array',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}
