<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get(
  'auth/{provider}',
  [\App\Http\Controllers\Social\SocialiteController::class, 'redirectToProvider'
])->name('auth.socialite.redirect');

Route::get(
  'auth/{provider}/callback',
  [\App\Http\Controllers\Social\SocialiteController::class, 'handleProviderCallback']
)->name('auth.socialite.callback');

Route::get(
  '/',
  \App\Http\Controllers\MapIndex::class,
)->name('home');

Route::get(
  '/i/{pharmacy:uuid}',
  \App\Http\Controllers\Pharmacy\Info::class,
)->name('pharmacies.quick.info');

Route::get('/privacy-policy', function () {
  return Inertia::render('Dashboard');
});
require __DIR__ . '/auth.php';
