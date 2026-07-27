<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'category',
        'description',
        'address',
        'city',
        'wa_number',
        'logo_path',
        'monthly_fixed_cost',
        'verification_status',
        'verification_note',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'monthly_fixed_cost' => 'decimal:2',
            'verified_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function aiChats(): HasMany
    {
        return $this->hasMany(AiChat::class);
    }

    public function marketingContents(): HasMany
    {
        return $this->hasMany(MarketingContent::class);
    }

    public function aiInsights(): HasMany
    {
        return $this->hasMany(AiInsight::class);
    }

    public function waMessages(): HasMany
    {
        return $this->hasMany(WaMessage::class);
    }

    public function igPosts(): HasMany
    {
        return $this->hasMany(IgPost::class);
    }

    public function programRegistrations(): HasMany
    {
        return $this->hasMany(ProgramRegistration::class);
    }

    public function isVerified(): bool
    {
        return $this->verification_status === 'verified';
    }
}
