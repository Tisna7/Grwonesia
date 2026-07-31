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

  protected $appends = [
    'image_url',
    'image',
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

  public function getImageAttribute(): string
  {
    return $this->image_url;
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

  protected static function booted(): void
  {
    static::created(function (Product $product) {
      if ($product->status === 'active') {
        try {
          $waService = app(\App\Services\WhatsApp\WhatsAppService::class);
          $waService->postProductToChannel($product);
        } catch (\Exception $e) {
          \Illuminate\Support\Facades\Log::error('Error posting product to WhatsApp channel: ' . $e->getMessage());
        }
      }
    });
  }

  public function getImageUrlAttribute(): string
  {
    if ($this->photo_path) {
      if (str_starts_with($this->photo_path, 'http')) {
        return $this->photo_path;
      }
      return asset('storage/' . $this->photo_path);
    }

    $categorySlug = match (strtolower($this->category ?? '')) {
      'minuman', 'kopi' => 'kopi',
      'batik', 'fashion', 'pakaian' => 'batik',
      'kerajinan', 'craft' => 'kerajinan',
      default => 'makanan',
    };

    $defaultImages = [
      'kopi' => asset('images/products/kopi_gula_aren.webp'),
      'batik' => asset('images/products/batik_solo.webp'),
      'kerajinan' => asset('images/products/tas_anyaman.webp'),
      'makanan' => asset('images/products/keripik_kopi.webp'),
    ];

    return $defaultImages[$categorySlug] ?? asset('images/products/kopi_gula_aren.webp');
  }
}
