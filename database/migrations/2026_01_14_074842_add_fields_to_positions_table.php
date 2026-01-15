<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('positions', function (Blueprint $table) {
            // Existing massive hiring flag
            $table->boolean('is_mass_hiring')->default(false)->after('type_of_request');

            // New column to link massive positions in a batch
            $table->uuid('mass_group_id')->nullable()->after('is_mass_hiring');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->dropColumn('is_mass_hiring');
            $table->dropColumn('mass_group_id');
        });
    }
};
