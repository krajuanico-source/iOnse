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
    Schema::table('item_numbers', function (Blueprint $table) {
      $table->foreignId('fund_source_id')->nullable()->constrained('fund_sources')->onDelete('set null');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('item_numbers', function (Blueprint $table) {
      //
    });
  }
};
