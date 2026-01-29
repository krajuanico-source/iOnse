<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('work_experiences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->string('position_title')->nullable();
            $table->string('department_agency')->nullable();
            $table->string('status_of_appointment')->nullable();
            $table->string('government_service', 3)->nullable();
            $table->softdeletes();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_experiences');
    }
};
