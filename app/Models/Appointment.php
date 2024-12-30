<?php

namespace App\Models;

use App\Traits\BootUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
  use HasFactory, BootUuid;

  protected $fillable = [
    'pharmacy_id',
    'user_id',
    'status',
    'cancellation_reason',
    'appointment_time',
  ];

  public function pharmacy()
  {
    return $this->belongsTo(Pharmacy::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
