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
    Schema::table('applicants', function (Blueprint $table) {
      $table->string('mobile_no')->nullable()->after('remarks'); // optional mobile number
      $table->string('email')->nullable()->after('mobile_no'); // optional email
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('applicants', function (Blueprint $table) {
      $table->dropColumn(['email', 'mobile_no']);
    });
  }
};
