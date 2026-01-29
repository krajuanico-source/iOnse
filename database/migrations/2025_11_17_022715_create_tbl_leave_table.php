<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('tbl_leave', function (Blueprint $table) {
      $table->id('leave_no'); // primary key
      $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // FK to users
      $table->string('leave_type');
      $table->string('leave_type_specify')->nullable();
      $table->string('leave_spent')->nullable();
      $table->string('leave_specify')->nullable();
      $table->integer('leave_no_wdays')->nullable();
      $table->string('leave_com')->nullable();
      $table->date('from_date')->nullable();
      $table->date('to_date')->nullable();
      $table->decimal('leave_credit_bal', 8, 2)->nullable();
      $table->decimal('leave_avail', 8, 2)->nullable();
      $table->string('leave_approved_by')->nullable();
      $table->string('leave_recommend_status')->nullable();
      $table->string('leave_recommend_by')->nullable();
      $table->boolean('leave_wpay')->default(false);
      $table->boolean('leave_wo_pay')->default(false);
      $table->text('leave_remarks')->nullable();
      $table->string('leave_authorized_by')->nullable();
      $table->date('date_applied')->nullable();
      $table->date('date_approved')->nullable();
      $table->decimal('leave_credit_bal_sick', 8, 2)->nullable();
      $table->decimal('leave_credit_bal_vac', 8, 2)->nullable();
      $table->string('status')->default('Pending');
      $table->string('pas_head')->nullable();
      $table->date('datefiled')->nullable();
      $table->string('other_purpose')->nullable();
      $table->softdeletes();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('tbl_leave');
  }
};
