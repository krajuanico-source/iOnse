<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('eligibilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('eligibility')->nullable();
            $table->decimal('rating', 5, 2)->nullable();
            $table->date('exam_date')->nullable();
            $table->string('exam_place')->nullable();
            $table->string('license_number')->nullable();
            $table->date('license_validity')->nullable();
            $table->softdeletes(); 
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eligibilities');
    }
};
