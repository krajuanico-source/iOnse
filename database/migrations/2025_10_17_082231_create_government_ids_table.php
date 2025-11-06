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
            $table->string('tin')->nullable();

            $table->string('gov_issued_id')->nullable();       // e.g. Driver's License
            $table->string('id_number')->nullable();           // e.g. License/Passport number
            $table->date('date_issuance')->nullable();
            $table->string('place_issuance')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('government_ids');
    }
};
