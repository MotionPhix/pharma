<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {

  Route::get(
    'login',
    [AuthenticatedSessionController::class, 'create']
  )->name('login');

  Route::post(
    'get-token',
    [\App\Http\Controllers\Auth\EmailLoginController::class, 'sendLoginToken']
  )->name('auth.request.token');

  Route::get(
    'verify-token',
    [AuthenticatedSessionController::class, 'verifyToken']
  )->name('auth.verify.token');

  Route::post(
    'get-user',
    [\App\Http\Controllers\Auth\EmailLoginController::class, 'login']
  )->name('auth.get.user');
});

Route::middleware('auth')->group(function () {
  Route::get('verify-email', EmailVerificationPromptController::class)
    ->name('verification.notice');

  Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

  Route::post(
    'email/verification-notification',
    [EmailVerificationNotificationController::class, 'store']
  )->middleware('throttle:6,1')
    ->name('verification.send');

  Route::get(
    'confirm-password',
    [ConfirmablePasswordController::class, 'show']
  )->name('password.confirm');

  Route::post(
    'confirm-password',
    [ConfirmablePasswordController::class, 'store']
  );

  Route::post(
    'logout',
    [AuthenticatedSessionController::class, 'destroy']
  )->name('logout');

  Route::prefix('profile')->group(function () {
    Route::get(
      '/',
      [ProfileController::class, 'edit']
    )->name('profile.edit');

    Route::patch(
      '/p',
      [ProfileController::class, 'update']
    )->name('profile.update');

    Route::delete(
      '/d',
      [ProfileController::class, 'destroy']
    )->name('profile.destroy');
  });

  Route::prefix('pharmacies')->group(function () {

    Route::get(
      '/favorites',
      \App\Http\Controllers\Favorite\Index::class,
    )->name('pharmacies.favorites.index');

    Route::post(
      '/favorites/{pharmacyId}',
      \App\Http\Controllers\Favorite\Add::class
    )->name('pharmacies.favorites.add');

    Route::delete(
      '/favorites/{pharmacyId}',
      \App\Http\Controllers\Favorite\Remove::class
    )->name('pharmacies.favorites.remove');

    Route::get(
      '/ratings/{pharmacy:uuid}',
      \App\Http\Controllers\Rating\Index::class
    )->name('pharmacies.ratings.index');

    Route::post(
      '/ratings/{pharmacy:uuid}',
      \App\Http\Controllers\Rating\HandleRatings::class
    )->name('pharmacies.ratings.handle');

    Route::get(
      '/s/{pharmacy:uuid}',
      \App\Http\Controllers\Pharmacy\Show::class,
    )->name('pharmacies.show');

    Route::get(
      '/c/appointment/{pharmacy:uuid}',
      \App\Http\Controllers\Appointment\Form::class,
    )->name('pharmacies.appointment.create');

    Route::post(
      '/b/appointment/{pharmacy:uuid}',
      \App\Http\Controllers\Appointment\Store::class,
    )->name('pharmacies.appointment.book');

    Route::get(
      '/',
      \App\Http\Controllers\Pharmacy\Index::class,
    )->name('pharmacies.index');

  });

});
