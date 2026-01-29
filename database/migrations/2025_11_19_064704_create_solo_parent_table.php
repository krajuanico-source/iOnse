<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('solo_parent', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // FK to users
      $table->string('circumstance')->nullable();
      $table->string('circumstance_other')->nullable();
      $table->string('status')->nullable();
      $table->softdeletes();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('solo_parent');
  }
};
