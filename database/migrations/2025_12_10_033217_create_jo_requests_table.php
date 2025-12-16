<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('jo_requests', function (Blueprint $table) {
      $table->id(); // id
      $table->string('subject');
      $table->string('type');
      $table->string('position_name');
      $table->integer('no_of_position');
      $table->date('effectivity_of_position');
      $table->unsignedBigInteger('fund_source_id');
      $table->text('remarks')->nullable();
      $table->string('status')->default('pending'); // default value
      $table->timestamps(); // created_at & updated_at

      // Optional: foreign key
      // $table->foreign('fund_source_id')->references('id')->on('fund_sources')->onDelete('cascade');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('jo_requests');
  }
};
