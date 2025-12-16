<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('cprs', function (Blueprint $table) {
      $table->id();

      // Store year-month only (YYYY-MM)
      $table->string('rating_period_start', 7);  // Example: 2025-12

      // Semester
      $table->string('semester'); // "1st Semester" or "2nd Semester"

      // Status
      $table->string('status')->default('Active');

      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('cprs');
  }
};
