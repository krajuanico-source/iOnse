<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('salary_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tranche_id')
                ->constrained('salary_tranche') // reference 'id'
                ->onDelete('cascade');
            $table->tinyInteger('salary_grade')->unsigned();
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['tranche_id', 'salary_grade']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_grades');
    }
};
