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
            // Drop old string column if exists
            if (Schema::hasColumn('positions', 'fund_source')) {
                $table->dropColumn('fund_source');
            }

            // Add new foreign key column
            $table->foreignId('fund_source_id')
                  ->nullable()
                  ->constrained('fund_sources')
                  ->nullOnDelete()
                  ->after('obsu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->dropForeign(['fund_source_id']);
            $table->dropColumn('fund_source_id');

            // Restore old string column
            $table->string('fund_source')->nullable()->after('obsu');
        });
    }
};
