<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('artas', function (Blueprint $table) {
      $table->id();

      $table->string('employee_id')->nullable();

      // Foreign keys
      $table->unsignedBigInteger('position_id')->nullable();
      $table->unsignedBigInteger('division_id')->nullable();
      $table->unsignedBigInteger('section_id')->nullable();
      $table->unsignedBigInteger('assignment_id')->nullable();

      $table->string('dob')->nullable();
      $table->string('email')->nullable();

      $table->string('status')->default('Pending');

      $table->timestamps();

      // Add FK constraints (optional, but recommended)
      $table->foreign('position_id')->references('id')->on('positions')->nullOnDelete();
      $table->foreign('division_id')->references('id')->on('divisions')->nullOnDelete();
      $table->foreign('section_id')->references('id')->on('sections')->nullOnDelete();
      $table->foreign('assignment_id')->references('id')->on('office_locations')->nullOnDelete();
    });
  }


  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('artas');
  }
};
