<?php

namespace Database\Seeders;

use App\Models\Pharmacy;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $pharmacies = Pharmacy::all();

    $comments = [
      'Amazing service and friendly staff!',
      'Quick and efficient support.',
      'Very helpful pharmacists.',
      'The place is clean and organized.',
      'Affordable prices and great products.',
      'Excellent customer service!',
      'Knowledgeable staff and prompt assistance.',
      'Highly recommend this pharmacy.',
      'Good experience overall.',
      'They go above and beyond to help.'
    ];

    foreach ($pharmacies as $pharmacy) {
      $pharmacy->ratings()->create([
        'user_id' => User::role('member')->inRandomOrder()->first()->id,
        'rating' => rand(1, 5), // Random rating between 1 and 5
        'review' => $comments[array_rand($comments)],
      ]);
    }
  }
}
