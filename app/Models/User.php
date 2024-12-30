<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
  /** @use HasFactory<\Database\Factories\UserFactory> */
  use HasFactory, Notifiable, HasRoles;
  use HasApiTokens;

  /**
   * The attributes that are mass assignable.
   *
   * @var list<string>
   */
  protected $fillable = [
    'name',
    'email',
    'avatar',
    'provider',
    'provider_id',
    'login_token',
    'token_expires_at',
    'failed_token_attempts',
    'lockout_until',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var list<string>
   */
  protected $hidden = [
    'password',
    'remember_token',
    'provider_id',
    'login_token',
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
      'token_expires_at' => 'datetime',
      'lockout_until' => 'datetime',
    ];
  }

  public function favorites(): HasMany
  {
    return $this->hasMany(Favorite::class);
  }

  /**
   * Pharmacies owned by the user.
   */
  public function pharmacies(): HasMany
  {
    return $this->hasMany(Pharmacy::class, 'owner_id');
  }
}
