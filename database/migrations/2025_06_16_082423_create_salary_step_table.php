<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('salary_step', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_id')
                ->constrained('salary_grades') // reference 'id'
                ->onDelete('cascade');
            $table->tinyInteger('step')->unsigned();
            $table->decimal('salary_amount', 12, 2);
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['grade_id', 'step']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_step');
    }
};
