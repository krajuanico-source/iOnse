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
        Schema::table('positions', function (Blueprint $table) {
            // Change enum values
            $table->enum('status', ['Filled', 'Unfilled', 'Newly-Created'])
                  ->default('Newly-Created')
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            // Revert back to original values
            $table->enum('status', ['active', 'inactive'])
                  ->default('active')
                  ->change();
        });
    }
};
