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
    Schema::create('applicants', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('item_number_id');
      $table->string('applicant_no')->unique();
      $table->string('first_name');
      $table->string('middle_name')->nullable();
      $table->string('last_name');
      $table->string('extension_name')->nullable();
      $table->enum('sex', ['Male', 'Female', 'Other']);
      $table->date('date_of_birth');
      $table->date('date_applied');
      $table->enum('status', [
        'Pending',
        'Examination',
        'Deliberation',
        'Hired',
        'Rejected',
        'Submission of Requirements',
        'On-Boarding'
      ])->default('Pending');
      $table->text('remarks')->nullable();
      $table->date('date_hired')->nullable();
      $table->timestamps();

      $table->foreign('item_number_id')->references('id')->on('item_numbers')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('applicants');
  }
};
