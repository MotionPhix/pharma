<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BootUuid;

class Service extends Model
{
  use HasFactory, BootUuid;

  protected $fillable = ['pharmacy_id', 'name', 'description',];

  public function pharmacy()
  {
    return $this->belongsTo(Pharmacy::class);
  }
}
