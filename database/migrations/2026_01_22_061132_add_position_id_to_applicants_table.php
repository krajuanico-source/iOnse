<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        /**
         * 1️⃣ DROP item_number_id (FK + column) IF EXISTS
         */
        Schema::table('applicants', function (Blueprint $table) {
            if (Schema::hasColumn('applicants', 'item_number_id')) {
                // Drop FK first (Laravel default naming)
                try {
                    $table->dropForeign(['item_number_id']);
                } catch (\Throwable $e) {
                    // FK might not exist — ignore safely
                }

                $table->dropColumn('item_number_id');
            }
        });

        /**
         * 2️⃣ ADD position_id COLUMN IF MISSING
         */
        Schema::table('applicants', function (Blueprint $table) {
            if (!Schema::hasColumn('applicants', 'position_id')) {
                $table->unsignedBigInteger('position_id')->nullable()->after('id');
            }
        });

        /**
         * 3️⃣ GET A GUARANTEED VALID POSITION
         */
        $defaultPositionId = DB::table('positions')->orderBy('id')->value('id');

        if (!$defaultPositionId) {
            throw new Exception('❌ No positions exist. Please create at least ONE position first.');
        }

        /**
         * 4️⃣ FORCE ALL APPLICANTS TO HAVE VALID position_id
         */
        DB::table('applicants')->update([
            'position_id' => $defaultPositionId
        ]);

        /**
         * 5️⃣ ENFORCE NOT NULL
         */
        Schema::table('applicants', function (Blueprint $table) {
            $table->unsignedBigInteger('position_id')->nullable(false)->change();
        });

        /**
         * 6️⃣ ADD FOREIGN KEY
         */
        Schema::table('applicants', function (Blueprint $table) {
            $table->foreign('position_id')
                  ->references('id')
                  ->on('positions')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('applicants', function (Blueprint $table) {
            // Drop position FK
            try {
                $table->dropForeign(['position_id']);
            } catch (\Throwable $e) {}

            // Make nullable again
            $table->unsignedBigInteger('position_id')->nullable()->change();

            // Restore item_number_id (optional, but correct rollback)
            $table->unsignedBigInteger('item_number_id')->nullable();
        });
    }
};
