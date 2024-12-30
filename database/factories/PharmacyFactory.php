<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pharmacy>
 */
class PharmacyFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'name' => fake()->company(),
      'address' => fake()->address(),
      'latitude' => fake()->latitude(),
      'longitude' => fake()->longitude(),
      'phone' => fake()->phoneNumber(),
      'type' => fake()->randomElement([
        'community pharmacy',
        'hospital pharmacy',
        'clinical pharmacy',
        'industrial pharmacy',
        'compounding pharmacy',
        'consulting pharmacy',
        'ambulatory care pharmacy',
        'regulatory pharmacy',
        'home care pharmacy'
      ]),
      'website' => fake()->url(),
      'owner_id' => User::factory(),
      'email' => fake()->unique()->companyEmail()
    ];
  }
}
