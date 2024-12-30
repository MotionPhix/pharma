<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorite extends Model
{
  protected $fillable = ['user_id', 'pharmacy_id'];

  // Define the relationship with User
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  // Define the relationship with Pharmacy
  public function pharmacy(): BelongsTo
  {
    return $this->belongsTo(Pharmacy::class);
  }
}
