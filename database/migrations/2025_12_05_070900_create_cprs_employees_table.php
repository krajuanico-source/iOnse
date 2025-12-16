<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
  {
    Schema::create('cpr_employees', function (Blueprint $table) {
      $table->id();

      // employee_id = reference to users table
      $table->unsignedBigInteger('employee_id');

      // cpr_id = reference to cprs table
      $table->unsignedBigInteger('cpr_id');

      // rating (string or integer depending on your needs)
      $table->string('rating')->nullable();

      $table->timestamps();

      // Add foreign keys
      $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
      $table->foreign('cpr_id')->references('id')->on('cprs')->onDelete('cascade');
    });
  }

  public function down()
  {
    Schema::dropIfExists('cpr_employees');
  }
};
