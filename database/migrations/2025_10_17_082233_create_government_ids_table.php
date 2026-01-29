<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('government_ids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('sss_id')->nullable();
            $table->string('gsis_id')->nullable();
            $table->string('pagibig_id')->nullable();
            $table->string('philhealth_id')->nullable();
            $table->string('tin_id')->nullable();

            $table->string('government_issued_id')->nullable();   
            $table->string('id_number')->nullable();        
            $table->date('date_issuance')->nullable();
            $table->string('place_issuance')->nullable();
            $table->softdeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('government_ids');
    }
};
