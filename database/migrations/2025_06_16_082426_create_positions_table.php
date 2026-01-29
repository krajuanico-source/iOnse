<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('position_level_id')->constrained('position_levels')->cascadeOnDelete();
            $table->foreignId('salary_grade_id')->constrained('salary_grades')->cascadeOnDelete();
            $table->foreignId('employment_status_id')->constrained('employment_statuses')->cascadeOnDelete();
            $table->foreignId('division_id')->constrained('divisions')->cascadeOnDelete();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->foreignId('office_location_id')->constrained('office_locations')->cascadeOnDelete();
            $table->foreignId('salary_tranche_id')->constrained('salary_tranches')->cascadeOnDelete();
            $table->foreignId('salary_step_id')->constrained('salary_step')->cascadeOnDelete();
            $table->foreignId('fund_source_id')->constrained('fund_sources')->cascadeOnDelete();

            // Position details
            $table->string('position_name');
            $table->string('abbreviation')->nullable();
            $table->string('item_no')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');

            // Additional fields
            $table->date('date_of_publication')->nullable();
            $table->string('program')->nullable();
            $table->decimal('monthly_rate', 15, 2)->nullable();
            $table->string('designation')->nullable();
            $table->string('level')->nullable();
            $table->string('parenthetical_title')->nullable();
            $table->string('special_order')->nullable();
            $table->string('obsu')->nullable();
            $table->enum('type_of_request', ['Direct Release','CMF'])->nullable();
            $table->boolean('is_mass_hiring')->default(false);
            $table->string('mass_group_id')->nullable();
            $table->softDeletes(); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
