<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::table('users')->insert([
      'name' => 'Your Name Here',
      'email' => 'tbadlwanjr@dswd.gov.ph',
      'password' => Hash::make('password123'), // default password
      'role' => 'HR-Planning',
      'email_verified_at' => now(),
      'created_at' => now(),
      'updated_at' => now(),
    ]);
  }

  //Sample
}
