<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('tbl_request', function (Blueprint $table) {
      $table->id('req_num'); // Primary key
      $table->string('empid')->nullable();
      $table->date('req_date')->nullable();
      $table->string('req_doc')->nullable();
      $table->string('req_period')->nullable();
      $table->string('req_purpose')->nullable();
      $table->string('req_specify')->nullable();
      $table->string('req_mode')->nullable();
      $table->string('req_status')->nullable();
      $table->date('req_date_released')->nullable();
      $table->string('req_incharge')->nullable();
      $table->date('req_date_recieved')->nullable();
      $table->string('req_released_by')->nullable();
      $table->string('scan_file')->nullable();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('tbl_request');
  }
};
