<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('tbl_travel', function (Blueprint $table) {
      $table->id('id_travel');
      $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // FK to users
      $table->date('date_requested');
      $table->date('travel_date');
      $table->string('travel_purpose');
      $table->string('travel_designation')->nullable();
      $table->string('travel_off_station')->nullable();
      $table->string('travel_destination');
      $table->string('travel_number')->nullable();
      $table->string('travel_status')->default('Pending');
      $table->string('travel_approved_by')->nullable();
      $table->date('travel_date_approved')->nullable();
      $table->string('travel_app_chief')->nullable();
      $table->text('travel_remarks')->nullable();
      $table->time('travel_inc_time')->nullable();
      $table->time('travel_exc_time')->nullable();
      $table->string('travel_prescribed')->nullable();
      $table->string('travel_request_by')->nullable();
      $table->string('file_image')->nullable();
      $table->string('pdf_file')->nullable();
      $table->string('status')->default('Active');
      $table->softdeletes();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('tbl_travel');
  }
};
