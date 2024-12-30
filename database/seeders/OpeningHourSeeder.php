<?php

namespace Database\Seeders;

use App\Models\Pharmacy;
use Illuminate\Database\Seeder;

class OpeningHourSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $openingHoursTemplate = [
      ['day' => 'Monday', 'open_time' => '09:00:00', 'close_time' => '18:00:00'],
      ['day' => 'Tuesday', 'open_time' => '09:00:00', 'close_time' => '18:00:00'],
      ['day' => 'Wednesday', 'open_time' => '09:00:00', 'close_time' => '18:00:00'],
      ['day' => 'Thursday', 'open_time' => '09:00:00', 'close_time' => '18:00:00'],
      ['day' => 'Friday', 'open_time' => '09:00:00', 'close_time' => '18:00:00'],
      ['day' => 'Saturday', 'open_time' => '10:00:00', 'close_time' => '16:00:00'],
      ['day' => 'Sunday', 'open_time' => null, 'close_time' => null],
    ];

    $pharmacies = Pharmacy::all();

    foreach ($pharmacies as $pharmacy) {
      foreach ($openingHoursTemplate as $openingHour) {
        $pharmacy->openingHours()->create([
          'day' => $openingHour['day'],
          'open_time' => $openingHour['open_time'],
          'close_time' => $openingHour['close_time'],
        ]);
      }
    }
  }
}
