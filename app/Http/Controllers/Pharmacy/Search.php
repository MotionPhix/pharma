<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use Illuminate\Http\Request;

class Search extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Request $request)
  {
    $query = Pharmacy::query();

    if ($request->has('name')) {
      $query->where('name', 'like', '%' . $request->name . '%');
    }

    if ($request->has('type')) {
      $query->where('type', $request->type); // Assuming 'type' column exists
    }

    /*if ($request->has('services')) {
      $query->whereHas('services', function ($q) use ($request) {
        $q->whereIn('id', $request->services); // Assuming services is a related table
      });
    }*/

    if ($request->has('services')) {
      $query->whereHas('services', function ($q) use ($request) {
        $q->whereIn('id', $request->services);
      });
    }

    if ($request->has(['latitude', 'longitude', 'distance'])) {
      $latitude = $request->latitude;
      $longitude = $request->longitude;
      $distance = $request->distance;

      $query->selectRaw("*, (
          6371 * acos(
              cos(radians(?)) *
              cos(radians(latitude)) *
              cos(radians(longitude) - radians(?)) +
              sin(radians(?)) *
              sin(radians(latitude))
          )
      ) AS distance", [$latitude, $longitude, $latitude])
        ->having('distance', '<=', $distance);
    }

    $pharmacies = $query->get()->map(function ($pharmacy) {
      $pharmacy->distance = round($pharmacy->distance, 2); // Round to 2 decimals
      return $pharmacy;
    });

    // $pharmacies = $query->limit(20)->get(); // Add pagination if needed

    return response()->json($pharmacies);
  }
}
