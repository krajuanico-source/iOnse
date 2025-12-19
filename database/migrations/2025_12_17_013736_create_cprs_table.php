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

      // Requestor (user who submitted the CPR)
      $table->foreignId('requestor_id')
        ->nullable()
        ->constrained('users')
        ->onDelete('set null');

      // Rating period (YYYY-MM)
      $table->string('rating_period_start', 7);

      // Semester
      $table->string('semester');

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
