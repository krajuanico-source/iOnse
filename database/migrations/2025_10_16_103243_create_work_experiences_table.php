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
        Schema::create('work_experiences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');  // links to users table
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->string('position_title')->nullable();
            $table->string('department_agency')->nullable();
            $table->decimal('monthly_salary', 15, 2)->nullable();
            $table->string('salary_grade')->nullable(); // e.g. "11-1"
            $table->string('status_of_appointment')->nullable();
            $table->string('govt_service', 3)->nullable(); // Y or N
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_experiences');
    }
};
