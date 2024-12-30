<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
  /**
   * The current password being used by the factory.
   */
  protected static ?string $password;

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'name' => fake()->name(),
      'email' => fake()->unique()->safeEmail(),
      'avatar' => fake()->imageUrl(100, 100, 'people'),
      'provider' => fake()->randomElement(['google', 'facebook']),
      'provider_id' => Str::orderedUuid(),
      'login_token' => null,
      'token_expires_at' => null,
      'failed_token_attempts' => 0,
      'lockout_until' => null,
      'email_verified_at' => now(),
      'remember_token' => Str::random(10),
    ];
  }

  /**
   * Indicate that the model's email address should be unverified.
   */
  public function unverified(): static
  {
    return $this->state(fn(array $attributes) => [
      'email_verified_at' => null,
    ]);
  }

  /**
   * Assign the 'admin' role to the user.
   */
  public function admin(): static
  {
    return $this->afterCreating(function (User $user) {
      $user->assignRole('admin');
    });
  }

  /**
   * Assign the 'client' role to the user.
   */
  public function client(): static
  {
    return $this->afterCreating(function (User $user) {
      $user->assignRole('client');
    });
  }

  /**
   * Assign the 'member' role to the user.
   */
  public function member(): static
  {
    return $this->afterCreating(function (User $user) {
      $user->assignRole('member');
    });
  }
}
