<?php

/*namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Exception;

class EmailLoginController extends Controller
{
  public function sendLoginToken(Request $request)
  {
    $request->validate([
      'email' => 'required|email',
    ]);

    $user = User::where('email', $request->email)->first();

    if ($user) {
      try {
        $token = Str::random(60);
        $user->update(['login_token' => hash('sha256', $token)]);

        Mail::send('emails.send-login-token', ['token' => $token], function ($message) use ($user) {
          $message->to($user->email);
          $message->subject('Your Login Token');
        });

        return back()->with('status', 'We have emailed your login token!');
      } catch (Exception $e) {
        Log::error('Failed to send login token', ['error' => $e->getMessage()]);
        return back()->withErrors(['email' => 'Failed to send login token. Please try again later.']);
      }
    }

    return back()->withErrors(['email' => 'The provided email does not match our records.']);
  }

  public function login(string $token)
  {
    try {
      $user = User::where('login_token', hash('sha256', $token))->firstOrFail();

      $user->update(['login_token' => null]);

      Auth::login($user, true);

      return redirect()->intended('/dashboard');
    } catch (Exception $e) {
      Log::error('Token login failed', ['error' => $e->getMessage()]);
      return redirect()->route('login')->withErrors(['token' => 'Invalid or expired login token.']);
    }
  }
}*/

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class EmailLoginController extends Controller
{
  /**
   * Send a login token to the user's email if they exist in the system.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function sendLoginToken(Request $request)
  {
    $request->validate([
      'email' => 'required|email',
    ], [
      'email.required' => 'Please enter your email address',
      'email.email' => 'You entered an invalid email address'
    ]);

    $user = User::where('email', $request->email)->first();

    if ($user && $user->id) {
      if ($user->lockout_until && Carbon::now()->lessThan($user->lockout_until)) {
        return back()->withErrors([
          'email' => 'Too many attempts. Please try again later.'
        ]);
      }

      if ($user->failed_token_attempts >= 3) {
        $user->update([
          'lockout_until' => Carbon::now()->addHour(),
          'failed_token_attempts' => 0
        ]);

        return back()->withErrors([
          'email' => 'Too many failed attempts. Please try again after an hour.'
        ]);
      }

      try {
        $token = Str::random(60);

        $user->update([
          'login_token' => hash('sha256', $token),
          'token_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        Mail::send(
          'emails.send-login-token',
          ['token' => $token],
          function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Your Login Token');
          }
        );

        return redirect(route('auth.verify.token'))
          ->with('status', 'We have emailed your login token!');
      } catch (Exception $e) {
        Log::error('Failed to send login token', ['error' => $e->getMessage()]);

        return back()->withErrors([
          'email' => 'Failed to send login token. Please try again later.'
        ]);
      }
    }

    return back()->withErrors([
      'email' => 'The provided email does not match our records.'
    ]);
  }

  /**
   * Log in the user using the token sent to their email.
   *
   * @param Request $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function login(Request $request)
  {
    try {
      $user = User::where('login_token', hash('sha256', $request->token))
        ->where('token_expires_at', '>', Carbon::now())
        ->firstOrFail();

      $user->update([
        'login_token' => null,
        'lockout_until' => null,
        'token_expires_at' => null,
        'failed_token_attempts' => 0,
      ]);

      Auth::login($user, true);

      return redirect()->intended(route('profile.edit'));
    } catch (Exception $e) {
      if (isset($user)) {
        $user->increment('failed_token_attempts');
      }

      Log::error('Token login failed', ['error' => $e->getMessage()]);

      return redirect('login')
        ->with([
          'status' => 'Invalid or expired login token.'
        ]);
    }
  }
}
