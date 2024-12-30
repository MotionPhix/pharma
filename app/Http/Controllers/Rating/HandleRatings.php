<?php

namespace App\Http\Controllers\Rating;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use App\Models\Rating;
use Illuminate\Http\Request;

class HandleRatings extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Request $request, Pharmacy $pharmacy)
  {
    $request->validate([
      'rating' => 'required|numeric|min:1|max:5',
      'review' => 'nullable|string|max:500',
    ]);

    $rating = Rating::updateOrCreate(
      [
        'user_id' => $request->user()->id,
        'pharmacy_id' => $pharmacy->id,
      ],
      [
        'rating' => $request->rating,
        'review' => $request->review,
      ]
    );

    $message = $this->generateMessage($rating->rating);

    if ($request->wantsJson()) {
      return response()->json([
        'message' => $message,
        'rating' => $rating
      ]);
    }

    return back()->with('status', $message);
  }

  /**
   * Generate a message based on the rating value.
   */
  private function generateMessage(float $rating): string
  {
    if ($rating < 2) {
      return "Ouch! A rating of $rating? Hopefully, they’ll take the hint and step up!";
    } elseif ($rating < 3) {
      return "A $rating rating, huh? Room for improvement, but not the worst!";
    } elseif ($rating > 3) {
      return "You rated the pharmacy with a whopping $rating! They must be doing something right!";
    }

    return "You gave a balanced $rating. Seems fair!";
  }
}
