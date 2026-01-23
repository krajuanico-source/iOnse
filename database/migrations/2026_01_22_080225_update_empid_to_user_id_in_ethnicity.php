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
        Schema::table('ethnicity', function (Blueprint $table) {
            // Rename empid to user_id
            $table->renameColumn('empid', 'user_id');

            // Change type to match users.id (unsignedBigInteger)
            $table->unsignedBigInteger('user_id')->change();

            // Add foreign key constraint
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ethnicity', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['user_id']);

            // Change type back to string if needed
            $table->string('user_id', 10)->change();

            // Rename back to empid
            $table->renameColumn('user_id', 'empid');
        });
    }
};
