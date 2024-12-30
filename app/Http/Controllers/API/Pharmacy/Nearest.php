<?php

namespace App\Http\Controllers\API\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use Illuminate\Http\Request;

class Nearest extends Controller
{
  /**
   * Fetch nearest pharmacies based on user's location.
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function __invoke(Request $request)
  {
    $request->validate([
      'latitude' => 'required|numeric',
      'longitude' => 'required|numeric',
      'limit' => 'nullable|integer|min:1',
    ]);

    $latitude = $request->latitude;
    $longitude = $request->longitude;
    $limit = $request->get('limit', 6);

    /*$pharmacies = Pharmacy::selectRaw(
      "id, name, latitude, longitude, address, phone,
      (6371 * acos(cos(radians(?)) * cos(radians(latitude))
      * cos(radians(longitude) - radians(?)) + sin(radians(?))
      * sin(radians(latitude)))) AS distance",
      [$latitude, $longitude, $latitude]
    )
      ->orderBy('distance')
      ->take($limit)
      ->get();*/

    $pharmacies = Pharmacy::selectRaw(
      "uuid, name, email, website, latitude, longitude, address, phone,
        (6371 * (CASE
          WHEN latitude IS NOT NULL AND longitude IS NOT NULL THEN
            sqrt(pow((latitude - ?), 2) + pow((longitude - ?), 2))
          ELSE
            0
        END)) AS distance",
      [$latitude, $longitude]
    )
      ->orderBy('distance')
      ->take($limit)
      ->get()->map(function ($pharmacy) {
        $pharmacy->distance = round($pharmacy->distance, 2); // Round to 2 decimals
        return $pharmacy;
      });

    return response()->json($pharmacies, 200);
  }
}
