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
    ProvinceSeeder::class,
    Barangayb2Seeder::class,
    Barangayb3Seeder::class,
    Barangayb4Seeder::class,
    Barangayb5Seeder::class,
    CitiesSeeder::class,
    UsersTableSeeder::class,
    ModulePermissionSeeder::class,
]);
  }
}
