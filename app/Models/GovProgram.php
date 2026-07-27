<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GovProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'type',
        'sector',
        'city',
        'description',
        'status',
        'starts_at',
        'ends_at',
        'ai_recommended',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'date',
            'ends_at' => 'date',
            'ai_recommended' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(ProgramRegistration::class);
    }

    /**
     * Program yang relevan untuk sebuah bisnis: aktif, belum lewat, dan
     * cocok kota/sektor (atau berlaku umum).
     */
    public function scopeRelevantFor(Builder $query, Business $business): Builder
    {
        return $query->where('status', 'aktif')
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhereDate('ends_at', '>=', now()))
            ->where(fn ($q) => $q->whereNull('city')->orWhere('city', $business->city))
            ->where(fn ($q) => $q->whereNull('sector')->orWhere('sector', $business->category));
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            'pelatihan' => '🎓 Pelatihan',
            'bantuan' => '🤝 Bantuan',
            'event' => '📅 Event',
            'pameran' => '🏛️ Pameran',
            default => $this->type,
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'aktif' => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/40',
            'selesai' => 'bg-slate-500/15 text-slate-400 border-slate-500/40',
            default => 'bg-amber-500/15 text-amber-400 border-amber-500/40',
        };
    }
}
