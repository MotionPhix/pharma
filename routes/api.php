<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
  return $request->user();
})->middleware('auth:sanctum');

Route::get(
  '/pharmacies/nearest',
  \App\Http\Controllers\API\Pharmacy\Nearest::class,
)->name('api.pharmacies.nearest');

Route::get(
  '/pharmacies/search',
  \App\Http\Controllers\Pharmacy\Search::class,
)->name('api.pharmacies.search');

Route::get(
  '/services/list',
  \App\Http\Controllers\API\Service\Index::class,
)->name('api.services.list');

