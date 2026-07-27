<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiRequest extends Model
{
    protected $fillable = [
        'model',
        'kind',
        'success',
        'duration_ms',
        'prompt_tokens',
        'output_tokens',
        'error',
    ];

    protected function casts(): array
    {
        return [
            'success' => 'boolean',
        ];
    }
}
