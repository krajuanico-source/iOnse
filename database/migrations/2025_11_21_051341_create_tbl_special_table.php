<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('tbl_special', function (Blueprint $table) {
      $table->id('id_special');
      $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // FK to users
      $table->string('special_subject');
      $table->date('special_from_date')->nullable();
      $table->date('special_to_date')->nullable();
      $table->date('special_date_request')->nullable();
      $table->string('special_purpose')->nullable();
      $table->string('special_number')->nullable();
      $table->date('special_date_approve')->nullable();
      $table->string('special_approve_by')->nullable();
      $table->string('special_to')->nullable();
      $table->string('special_from')->nullable();
      $table->string('special_requested_by')->nullable();
      $table->string('status')->nullable();
      $table->string('file_image')->nullable();
      $table->string('sp_section')->nullable();
      $table->string('sp_venue')->nullable();
      $table->string('training_type')->nullable();
      $table->string('pdf_file')->nullable();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('tbl_special');
  }
};
