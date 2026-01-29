<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('job_order_requests', function (Blueprint $table) {
      $table->id(); 
      $table->string('subject');
      $table->string('type');
      $table->string('position_name');
      $table->integer('no_of_position');
      $table->date('effectivity_of_position');
      $table->unsignedBigInteger('fund_source_id');
      $table->text('remarks')->nullable();
      $table->string('status')->default('pending');
      $table->timestamps(); 

    });
  }

  public function down(): void
  {
    Schema::dropIfExists('job_order_requests');
  }
};
