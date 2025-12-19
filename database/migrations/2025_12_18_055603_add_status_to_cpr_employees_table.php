<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::table('cpr_employees', function (Blueprint $table) {
      $table->string('status')->default('Pending')->after('cpr_file');
    });
  }

  public function down(): void
  {
    Schema::table('cpr_employees', function (Blueprint $table) {
      $table->dropColumn('status');
    });
  }
};
