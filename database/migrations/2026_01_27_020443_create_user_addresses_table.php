<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();

            // Foreign key
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // Permanent address
            $table->string('perm_country', 100)->nullable();
            $table->string('perm_region', 100)->nullable();
            $table->string('perm_province', 100)->nullable();
            $table->string('perm_city', 100)->nullable();
            $table->string('perm_barangay', 100)->nullable();
            $table->string('perm_street', 100)->nullable();
            $table->string('perm_house_no', 50)->nullable();
            $table->string('perm_village', 100)->nullable();
            $table->string('perm_zipcode', 20)->nullable();

            // Residential address
            $table->string('res_country', 100)->nullable();
            $table->string('res_region', 100)->nullable();
            $table->string('res_province', 100)->nullable();
            $table->string('res_city', 100)->nullable();
            $table->string('res_barangay', 100)->nullable();
            $table->string('res_street', 100)->nullable();
            $table->string('res_house_no', 50)->nullable();
            $table->string('res_village', 100)->nullable();
            $table->string('res_zipcode', 20)->nullable();
            $table->softdeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
