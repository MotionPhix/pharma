<?php

namespace App\Models;

use App\Traits\BootUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
  use HasFactory; //, BootUuid;

  protected $fillable = [
    'user_id',
    'pharmacy_id',
    'rating',
    'review',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function pharmacy(): BelongsTo
  {
    return $this->belongsTo(Pharmacy::class);
  }
}
