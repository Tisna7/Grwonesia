<?php

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
  /** @use HasFactory<UserFactory> */
  use HasFactory, Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var list<string>
   */
  protected $fillable = [
    'name',
    'email',
    'password',
    'role',
    'phone',
    'raw_phone',
    'status',
    'google_id',
    'avatar',
    'verification_code',
    'verification_expires_at',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var list<string>
   */
  protected $hidden = [
    'password',
    'remember_token',
    'verification_code',
  ];

  /**
   * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'email_verified_at' => 'datetime',
      'verification_expires_at' => 'datetime',
      'password' => 'hashed',
      'role' => UserRole::class,
    ];
  }

  public function business(): HasOne
  {
    return $this->hasOne(Business::class);
  }

  public function isBusiness(): bool
  {
    return $this->role === UserRole::Business;
  }

  public function isVerified(): bool
  {
    return $this->status === 'terverifikasi' || !is_null($this->email_verified_at);
  }

  public function getDashboardRouteName(): string
  {
    $roleValue = $this->role instanceof UserRole ? $this->role->value : (string) $this->role;

    return match ($roleValue) {
      'business' => 'business.dashboard',
      'government' => 'government.dashboard',
      'admin' => 'admin.dashboard',
      default => 'user.dashboard',
    };
  }
}
