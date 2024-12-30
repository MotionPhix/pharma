<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use Illuminate\Http\Request;

class Show extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Request $request, Pharmacy $pharmacy)
  {
    if (!$pharmacy) {
      return back()->withErrors(['message' => 'Pharmacy not found']);
    }

    return Inertia('Pharmacies/Show', [
      'pharmacy' => $pharmacy->load(['services', 'ratings']),
      'averageRating' => $pharmacy->ratings()->avg('rating'),
    ]);
  }
}
