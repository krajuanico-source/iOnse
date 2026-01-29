<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ethnicities', function (Blueprint $table) {
            $table->id();

            // FK
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Core ethnicity fields
            $table->string('ethnicity')->nullable();
            $table->string('ethnicity_other')->nullable();

            // Living condition
            $table->string('living_condition')->nullable();
            $table->string('living_condition_other')->nullable();

            // Special needs
            $table->boolean('special_needs')->default(false);
            $table->text('description')->nullable();

            // Demographic counts
            $table->integer('household_count')->default(0);
            $table->integer('zero_above')->default(0);
            $table->integer('six_above')->default(0);
            $table->integer('eighteen_above')->default(0);
            $table->integer('forty_six_above')->default(0);
            $table->integer('sixty_above')->default(0);
            $table->integer('children_still_studying')->default(0);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ethnicities');
    }
};
