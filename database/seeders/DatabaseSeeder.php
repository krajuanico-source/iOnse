<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // Create a test user
    User::factory()->create([
      'name' => 'Test User',
      'email' => 'test@example.com',
    ]);

    // Call individual seeders
    $this->call([
      RegionSeeder::class,
    ]);
    $this->call([
      ProvinceSeeder::class,
    ]);
    $this->call([
      Barangayb2Seeder::class,
    ]);
        $this->call([
      Barangayb3Seeder::class,
    ]);
        $this->call([
      Barangayb4Seeder::class,
    ]);
        $this->call([
      Barangayb5Seeder::class,
    ]);
        $this->call([
      ProvinceSeeder::class,
    ]);
        $this->call([
      CitiesSeeder::class,
    ]);
  }
}
