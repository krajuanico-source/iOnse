<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::table('cpr_employees', function (Blueprint $table) {
      $table->string('cpr_file')->nullable()->after('rating');
    });
  }

  public function down()
  {
    Schema::table('cpr_employees', function (Blueprint $table) {
      $table->dropColumn('cpr_file');
    });
  }
};
