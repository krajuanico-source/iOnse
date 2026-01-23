<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('family_backgrounds', function (Blueprint $table) {
            // Drop existing FK if it exists
            $table->dropForeign(['user_id']);

            // Make sure user_id is BIGINT UNSIGNED
            $table->unsignedBigInteger('user_id')->change();

            // Re-add the foreign key
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('family_backgrounds', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            // optionally change back type if needed
        });
    }
};
