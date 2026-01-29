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
    Schema::create('gender_and_development_responses', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // FK to users
      $table->string('gender', 50);
      $table->string('honorifics', 100);
      $table->string('move_member', 10);
      $table->string('gfps_twg', 10);
      $table->json('gad_challenges')->nullable();
      $table->json('gad_trainings')->nullable();
      $table->json('intervention_modes')->nullable();

      // Questions q1 - q26 as tiny integers (booleans or small numeric ratings)
      for ($i = 1; $i <= 26; $i++) {
        $table->tinyInteger('q' . $i);
      }
      $table->string('desired_mode', 100);
      $table->softdeletes();
      $table->timestamp('submitted_at')->useCurrent();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('gender_and_development_responses');
  }
};
