<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('education', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Link to the profile/user
            $table->string('level_of_education');
            $table->string('school_name');
            $table->string('degree_course')->nullable();
            $table->year('date_from')->nullable();
            $table->year('date_to')->nullable();
            $table->string('highest_level_earned')->nullable();
            $table->year('year_graduated')->nullable();
            $table->string('scholarship_honors')->nullable();
            $table->softdeletes(); 
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('education');
    }
};
