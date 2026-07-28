<?php
// FILE: app/Models/UserNotification.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserNotification extends Model
{
  use HasFactory;

  protected $table = 'user_notifications';

  protected $fillable = [
    'user_id',
    'title',
    'desc',
    'time_label',
    'is_read',
  ];

  protected $casts = [
    'is_read' => 'boolean',
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }
}
