<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('dual_citizenship', ['yes', 'no'])->default('no');
            $table->enum('citizenship_type', ['by_birth', 'by_naturalization'])->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['dual_citizenship', 'citizenship_type']);
        });
    }
};
