<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use Illuminate\Http\Request;

class Info extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Request $request, Pharmacy $pharmacy)
  {
    return Inertia('Pharmacy/QuickInfo', [
      'pharmacy' => $pharmacy->load(['services', 'openingHours'])
    ]);
  }
}
