<?php

namespace App\Http\Controllers\Rating;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Inertia\Inertia;

class Index extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Request $request, Pharmacy $pharmacy)
  {
    if (!$request->user()->can('rate pharmacy')) {
      return back()->with(
        'status', "You can't rate {$pharmacy->name}. Try again later"
      );
    }

    return Inertia::modal('Pharmacy/Partials/RatingForm', [
      'name' => $pharmacy->name,
      'uuid' => $pharmacy->uuid,
    ])->baseUrl('/pharmacies');
  }
}
