<?php

namespace App\Http\Controllers\Favorite;

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
    $favorites = $request->user()->favorites()
      ->with('pharmacy') // Include pharmacy details
      ->get();

    $recommendations = [];

    if ($favorites->isEmpty()) {
      // Assuming the user's location is available (latitude, longitude)
      $latitude = $request->query('latitude');
      $longitude = $request->query('longitude');

      if ($latitude && $longitude) {
        $recommendations = Pharmacy::query()
          ->whereNotNull('rating') // Ensure rating exists
          ->orderByDesc('rating') // Sort by rating
          ->limit(6) // Limit results
          ->get()
          ->filter(function ($pharmacy) use ($latitude, $longitude) {
            $distance = $this->calculateDistance(
              $latitude,
              $longitude,
              $pharmacy->latitude,
              $pharmacy->longitude
            );
            return $distance <= 10; // Only include pharmacies within 10 km
          });
      }
    }

    return Inertia('Favorite/Index', [
      'favorites' => $favorites,
      'recommendations' => $recommendations,
    ]);
  }

  private function calculateDistance($lat1, $lon1, $lat2, $lon2)
  {
    $earthRadius = 6371; // Earth's radius in km
    $latDiff = deg2rad($lat2 - $lat1);
    $lonDiff = deg2rad($lon2 - $lon1);

    $a = sin($latDiff / 2) * sin($latDiff / 2) +
      cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
      sin($lonDiff / 2) * sin($lonDiff / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return $earthRadius * $c; // Distance in km
  }
}
