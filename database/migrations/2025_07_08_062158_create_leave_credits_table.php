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
        Schema::create('leave_credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('first_name_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('middle_name_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('last_name_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('extension_name_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->decimal('vacation_leave', 12, 2);
            $table->decimal('sick_leave', 12, 2);
            $table->decimal('total_leave', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_credits');
    }
};
