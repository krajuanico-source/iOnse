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


    // Call seeders in correct dependency order
    $this->call([

      /*
            |--------------------------------------------------------------------------
            | Location / Geography
            |--------------------------------------------------------------------------
            */
      RegionSeeder::class,
      ProvinceSeeder::class,
      CitiesSeeder::class,
      Barangayb2Seeder::class,
      Barangayb3Seeder::class,
      Barangayb4Seeder::class,
      Barangayb5Seeder::class,
      BarangaysSeeder::class,
      PermissionSeeder::class,
      DivisionSeeder::class,
      SectionSeeder::class,
      OfficeLocationSeeder::class,
      UserSeeder::class,


      /*
            |--------------------------------------------------------------------------
            | Organizational Structure
            |--------------------------------------------------------------------------
            */
      // DivisionSeeder::class,
      // SectionSeeder::class,
      // OfficeLocationSeeder::class,

      /*
            |--------------------------------------------------------------------------
            | Employment / HR
            |--------------------------------------------------------------------------
      //       */
      // EmploymentStatusSeeder::class,

      /*
            |--------------------------------------------------------------------------
            | Users
            |--------------------------------------------------------------------------
            */
      // UserSeeder::class,
    ]);
  }
}
