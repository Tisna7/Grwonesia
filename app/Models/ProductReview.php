<?php
// FILE: app/Models/ProductReview.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReview extends Model
{
  use HasFactory;

  protected $fillable = [
    'product_id',
    'user_name',
    'rating',
    'comment',
    'verified',
  ];

  protected $casts = [
    'verified' => 'boolean',
    'rating' => 'integer',
  ];

  public function product(): BelongsTo
  {
    return $this->belongsTo(Product::class);
  }
}
