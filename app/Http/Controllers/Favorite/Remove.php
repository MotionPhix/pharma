<?php

namespace App\Http\Controllers\Favorite;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use Illuminate\Http\Request;

class Remove extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Request $request, Pharmacy $pharmacy)
  {
    $user = $request->user();
    $user->favorites()->detach($pharmacy->id);

    if ($request->wantsJson()) {
      return "something nice and cooler";
    }

    return back();
  }
}
