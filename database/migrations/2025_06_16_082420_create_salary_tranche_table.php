<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
       Schema::create('salary_tranche', function (Blueprint $table) {
            $table->id();
            $table->string('tranche_name', 100);
            $table->date('effectivity_date');
            $table->boolean('is_active')->default(false);
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['tranche_name', 'effectivity_date']);
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('salary_tranche');
    }
};
