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

    Schema::create('jo_requests', function (Blueprint $table) {
      $table->id();

      $table->string('subject'); // e.g. “Request for Extension”
      $table->enum('type', ['CMF', 'DR']);

      // Office foreign key (assuming 'offices' table)
      $table->unsignedBigInteger('section_id');
      $table->foreign('section_id')->references('id')->on('offices')->onDelete('cascade');

      $table->string('position_name');
      $table->unsignedInteger('no_of_position'); // number of positions
      $table->date('effectivity_of_position');

      // Fund source (assuming related table exists)
      $table->unsignedBigInteger('fund_source_id')->nullable();
      $table->foreign('fund_source_id')->references('id')->on('fund_sources')->onDelete('set null');

      $table->text('remarks')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('jo_requests');
  }
};
