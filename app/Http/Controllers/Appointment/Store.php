<?php

namespace App\Http\Controllers\Appointment;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use Illuminate\Http\Request;

class Store extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Request $request, Pharmacy $pharmacy)
  {
    $validated = $request->validate([
      'appointment_time' => 'required|date|after:now',
    ]);

    $pharmacy->appointments()->create([
      'user_id' => $request->user()->id,
      'appointment_time' => $validated['appointment_time'],
    ]);

    return redirect()->route('pharmacies.show', $pharmacy);
  }
}
