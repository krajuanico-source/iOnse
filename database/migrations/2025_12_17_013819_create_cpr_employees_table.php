<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('cpr_employees', function (Blueprint $table) {
      $table->id();

      // Reference to CPR
      $table->foreignId('cpr_id')
        ->constrained('cprs')
        ->onDelete('cascade');

      // Reference to employee (users table)
      $table->foreignId('employee_id')
        ->constrained('users')
        ->onDelete('cascade');

      // Employee rating
      $table->decimal('rating', 5, 2)->nullable();

      // Supporting file path
      $table->string('cpr_file')->nullable();

      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('cpr_employees');
  }
};
