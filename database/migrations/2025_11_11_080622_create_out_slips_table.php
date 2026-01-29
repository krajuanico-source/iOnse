<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('out_slips', function (Blueprint $table) {
      $table->id();
      $table->date('date');
      $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // FK to users
      $table->string('destination');
      $table->string('type_of_slip', 100);
      $table->text('purpose')->nullable();
      $table->string('status', 50)->default('Pending');

      $table->foreignId('approved_by')->nullable()
        ->constrained('users')
        ->nullOnDelete();

      $table->softdeletes();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('out_slips');
  }
};
