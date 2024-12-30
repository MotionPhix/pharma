<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PharmacySeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $pharmacies = [
      [
        'name' => 'City Pharmacy',
        'address' => 'Lilongwe City Mall, Lilongwe',
        'phone' => '123-456-7890',
        'website' => 'https://citypharmacy.com',
        'email' => 'info@citypharmacy.com',
      ],
      [
        'name' => 'Health First Pharmacy',
        'address' => 'Kamuzu Central Hospital, Lilongwe',
        'phone' => '987-654-3210',
        'website' => 'https://healthfirstpharmacy.com',
        'email' => 'contact@healthfirstpharmacy.com',
      ],
      [
        'name' => 'Wellness Pharmacy',
        'address' => 'Old Town Mall, Lilongwe',
        'phone' => '555-555-5555',
        'website' => 'https://wellnesspharmacy.com',
        'email' => 'support@wellnesspharmacy.com',
      ],
      [
        'name' => 'Bunda Pharmacy',
        'address' => 'Bunda College of Agriculture, Lilongwe',
        'phone' => '111-222-3333',
        'website' => 'https://bundapharmacy.com',
        'email' => 'info@bundapharmacy.com',
      ],
      [
        'name' => 'Four Seasons Pharmacy',
        'address' => 'Four Seasons Centre, Lilongwe',
        'phone' => '444-555-6666',
        'website' => 'https://fourseasonspharmacy.com',
        'email' => 'contact@fourseasonspharmacy.com',
      ],
      [
        'name' => 'Golf Club Pharmacy',
        'address' => 'Lilongwe Golf Club, Lilongwe',
        'phone' => '777-888-9999',
        'website' => 'https://golfclubpharmacy.com',
        'email' => 'info@golfclubpharmacy.com',
      ],
      [
        'name' => 'Crossroads Pharmacy',
        'address' => 'Crossroads Hotel, Lilongwe',
        'phone' => '222-333-4444',
        'website' => 'https://crossroadspharmacy.com',
        'email' => 'contact@crossroadspharmacy.com',
      ],
      [
        'name' => 'Area 18 Pharmacy',
        'address' => 'Area 18 Roundabout, Lilongwe',
        'phone' => '555-666-7777',
        'website' => 'https://area18pharmacy.com',
        'email' => 'info@area18pharmacy.com',
      ],
      [
        'name' => 'Capital Hill Pharmacy',
        'address' => 'Capital Hill, Lilongwe',
        'phone' => '888-999-0000',
        'website' => 'https://capitalhillpharmacy.com',
        'email' => 'contact@capitalhillpharmacy.com',
      ],
      [
        'name' => 'Wildlife Centre Pharmacy',
        'address' => 'Lilongwe Wildlife Centre, Lilongwe',
        'phone' => '333-444-5555',
        'website' => 'https://wildlifecentrepharmacy.com',
        'email' => 'info@wildlifecentrepharmacy.com',
      ],
    ];

    foreach ($pharmacies as $pharmacy) {

      $coordinates = $this->generateRandomCoordinates();

      $randomOwner = User::role('client')->inRandomOrder()->first();

      $pharmacy['owner_id'] = $randomOwner;
      $pharmacy['latitude'] = $coordinates[0]['lat'];
      $pharmacy['longitude'] = $coordinates[0]['lng'];

      \App\Models\Pharmacy::factory()->create($pharmacy);
    }
  }

  private function generateRandomCoordinates($numPlaces = 1)
  {
    $coordinates = [];
    $latMin = -13.9833; // Minimum latitude for Lilongwe
    $latMax = -13.7500; // Maximum latitude for Lilongwe
    $lngMin = 33.7000;  // Minimum longitude for Lilongwe
    $lngMax = 34.0000;  // Maximum longitude for Lilongwe

    while (count($coordinates) < $numPlaces) {
      $lat = mt_rand($latMin * 1000000, $latMax * 1000000) / 1000000;
      $lng = mt_rand($lngMin * 1000000, $lngMax * 1000000) / 1000000;
      $coord = ['lat' => $lat, 'lng' => $lng];

      if (!in_array($coord, $coordinates)) {
        $coordinates[] = $coord;
      }
    }

    return $coordinates;
  }
}
