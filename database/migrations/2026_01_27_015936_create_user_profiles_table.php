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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('office_location_id')->constrained('office_locations')->cascadeOnDelete();
            $table->foreignId('division_id')->constrained('divisions')->cascadeOnDelete();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->foreignId('employment_status_id')->constrained('employment_statuses')->cascadeOnDelete();

            // Personal info
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->string('extension_name', 50)->nullable();
            $table->date('birthday')->nullable();
            $table->enum('gender', ['Male','Female','Other'])->nullable();
            $table->string('civil_status', 50)->nullable();
            $table->string('blood_type', 3)->nullable();
            $table->decimal('height', 5, 2)->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->string('citizenship', 100)->nullable();
            $table->enum('citizenship_type', ['By Birth','By Naturalization'])->nullable();

            // Contact & account info
            $table->string('profile_image')->nullable();
            $table->string('deactivation_reason')->nullable();
            $table->string('tel_no', 20)->nullable();
            $table->string('mobile_no', 20)->nullable();
            $table->softdeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
