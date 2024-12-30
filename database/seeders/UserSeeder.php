<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $admin = User::factory()
      ->admin()
      ->create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
      ]);

    $client = User::factory(5)
      ->client()
      ->create();

    $member = User::factory(25)
      ->member()
      ->create();
  }
}
