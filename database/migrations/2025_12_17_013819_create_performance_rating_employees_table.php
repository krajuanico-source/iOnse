<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('performance_rating_employees', function (Blueprint $table) {
      $table->id();

      // Reference to CPR
      $table->foreignId('performance_rating_id')
        ->constrained('performance_rating')
        ->onDelete('cascade');

      $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // FK to users

      // Employee rating
      $table->decimal('rating', 5, 2)->nullable();

      // Supporting file path
      $table->string('performance_rating_file')->nullable();

      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('performance_rating_employees');
  }
};
