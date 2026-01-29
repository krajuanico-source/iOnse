<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('salary_grades', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('salary_tranche_id')
                  ->constrained('salary_tranches')
                  ->onDelete('cascade');
            
            $table->tinyInteger('salary_grade')->unsigned();
            
            $table->timestamps(); 
            $table->softDeletes();
            
            $table->unique(['salary_tranche_id', 'salary_grade']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_grades');
    }
};
