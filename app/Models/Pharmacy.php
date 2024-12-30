<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BootUuid;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pharmacy extends Model
{
  use BootUuid, HasFactory;

  protected $fillable = [
    'name',
    'address',
    'latitude',
    'longitude',
    'phone',
    'type',
    'website',
    'owner_id',
    'email'
  ];

  public function owner()
  {
    return $this->belongsTo(User::class, 'owner_id');
  }

  public function services()
  {
    return $this->hasMany(Service::class);
  }

  public function openingHours()
  {
    return $this->hasMany(OpeningHour::class);
  }

  public function favorites(): HasMany
  {
    return $this->hasMany(Favorite::class);
  }

  public function ratings()
  {
    return $this->hasMany(Rating::class);
  }

  public function averageRating()
  {
    return $this->ratings()->average('rating');
  }

  public function getAverageRatingAttribute()
  {
    return round($this->ratings()->avg('rating'), 1) ?? null;
  }
}
