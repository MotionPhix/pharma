<?php

namespace App\Http\Controllers\Social;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Exception;

class SocialiteController extends Controller
{
  /**
   * Redirect the user to the social provider authentication page.
   *
   * @param string $provider
   * @return \Illuminate\Http\RedirectResponse
   */
  public function redirectToProvider(string $provider)
  {
    return Socialite::driver($provider)->redirect();
  }

  /**
   * Obtain the user information from the social provider.
   *
   * @param string $provider
   * @return \Illuminate\Http\RedirectResponse
   */
  public function handleProviderCallback(string $provider)
  {
    try {
      $socialUser = Socialite::driver($provider)->stateless()->user();

      $user = User::updateOrCreate(
        [
          'email' => $socialUser->getEmail(),
        ],
        [
          'name' => $socialUser->getName() ?? Str::before($socialUser->getEmail(), '@'),
          'provider' => $provider,
          'provider_id' => $socialUser->getId(),
          'avatar' => $socialUser->getAvatar(),
        ]
      );

      Auth::login($user, true);

      return redirect()->intended('/');
    } catch (Exception $e) {
      Log::error('Socialite Login Error: ', [
        'provider' => $provider,
        'error' => $e->getMessage(),
      ]);

      return redirect()->route('auth.login')->withErrors([
        'social_login' => 'Failed to login using ' . ucfirst($provider) . '. Please try again.',
      ]);
    }
  }
}
