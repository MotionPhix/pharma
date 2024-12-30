<?php

namespace App\Http\Controllers\API\Service;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class Index extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Request $request)
  {
    $services = Service::select(['uuid', 'name'])->get();
    return response()->json($services);
  }
}
