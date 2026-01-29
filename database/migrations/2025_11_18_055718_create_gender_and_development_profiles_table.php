<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('gender_and_development_profiles', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // FK to users
      $table->string('gender')->nullable();
      $table->string('honorifics')->nullable();
      $table->string('other_honorifics')->nullable();
      $table->boolean('move_member')->default(0);
      $table->boolean('gfps_twg')->default(0);

      // Questions q1 – q26
      for ($i = 1; $i <= 26; $i++) {
        $table->string("q$i")->nullable();
      }

      $table->string('desired_mode')->nullable();
      $table->timestamp('submitted_at')->nullable();
      $table->softdeletes();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('gender_and_development_profiles');
  }
};
