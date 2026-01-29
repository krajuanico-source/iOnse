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
    Schema::create('authentic_copy_request_items', function (Blueprint $table) {
      $table->id();

      // Foreign key to authentic_copy_requests
      $table->foreignId('authentic_copy_request_id')
        ->constrained('authentic_copy_requests')
        ->cascadeOnDelete();

      // Foreign key to cprs
      $table->foreignId('performance_rating_id')
        ->constrained('performance_rating')
        ->cascadeOnDelete();

      $table->decimal('rating', 5, 2);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('authentic_copy_request_items');
  }
};
