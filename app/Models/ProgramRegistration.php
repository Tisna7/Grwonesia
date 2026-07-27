<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramRegistration extends Model
{
    protected $fillable = [
        'gov_program_id',
        'business_id',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(GovProgram::class, 'gov_program_id');
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}
