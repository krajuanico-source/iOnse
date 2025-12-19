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
        Schema::table('users', function (Blueprint $table) {
            // Dual Citizenship fields
            if (!Schema::hasColumn('users', 'dual_citizenship')) {
                $table->string('dual_citizenship')->nullable()->after('citizenship');
            }
            if (!Schema::hasColumn('users', 'citizenship_type')) {
                $table->enum('citizenship_type', ['by_birth', 'by_naturalization'])->nullable()->after('dual_citizenship');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'citizenship_type')) {
                $table->dropColumn('citizenship_type');
            }
            if (Schema::hasColumn('users', 'dual_citizenship')) {
                $table->dropColumn('dual_citizenship');
            }
        });
    }
};
