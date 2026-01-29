<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_and_developments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title_of_learning_and_development');
            $table->date('date_from');
            $table->date('date_to')->nullable();
            $table->integer('number_of_hours')->nullable();
            $table->string('type_of_learning_and_development')->nullable(); // Managerial / Supervisory / Technical / etc.
            $table->string('conducted_by')->nullable();
            $table->softdeletes();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_and_developments');
    }
};
