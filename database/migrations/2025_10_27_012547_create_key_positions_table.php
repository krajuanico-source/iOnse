<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('key_positions', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->string('successor_name')->nullable();
      $table->string('readiness_level')->nullable(); // e.g. Ready Now, 1 Year, 2+ Years
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('key_positions');
  }
};
