<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use Illuminate\Http\Request;

class Index extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Request $request)
  {
    $pharmacies = Pharmacy::with('ratings')->select(
      'uuid', 'name', 'email', 'website',
      'address', 'latitude',
      'longitude', 'phone', 'average_rating'
    )->get();
      /*->map(function ($pharmacy) {
      $pharmacy->average_rating = $pharmacy->averageRating();
      return $pharmacy;
    });*/

    return Inertia('Pharmacy/Index', [
      'pharmacies' => $pharmacies
    ]);
  }
}
