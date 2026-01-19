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
      $table->string('stature')->nullable()->after('status');
    });
  }

  public function down(): void
  {
    Schema::table('item_numbers', function (Blueprint $table) {
      $table->dropColumn('stature');
    });
  }
};
