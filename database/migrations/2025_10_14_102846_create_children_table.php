<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('children', function (Blueprint $table) {
            $table->id();
            $table->foreignId('family_background_id')
                  ->constrained('family_backgrounds')
                  ->onDelete('cascade');
            
            $table->string('first_name')->nullable(); 
            $table->string('middle_name')->nullable(); 
            $table->string('last_name')->nullable();   
            $table->date('birthday')->nullable();   
            $table->softdeletes();  
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('children');
    }
};
