<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('item_numbers', function (Blueprint $table) {
      $table->id();
      $table->string('item_number')->unique(); // e.g. "Item-001"
      $table->unsignedBigInteger('position_id');
      $table->unsignedBigInteger('salary_grade_id');
      $table->unsignedBigInteger('employment_status_id');
      $table->enum('status', ['active', 'inactive'])->default('active');
      $table->timestamps();

      // Foreign key constraints
      $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade');
      $table->foreign('salary_grade_id')->references('id')->on('salary_grades')->onDelete('cascade');
      $table->foreign('employment_status_id')->references('id')->on('employment_statuses')->onDelete('cascade');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('item_numbers');
  }
};
