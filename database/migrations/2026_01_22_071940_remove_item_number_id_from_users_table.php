<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1️⃣ Remove columns from users table
        Schema::table('users', function (Blueprint $table) {

            // Drop foreign key and column for item_number_id
            if (Schema::hasColumn('users', 'item_number_id')) {
                $table->dropForeign(['item_number_id']); // adjust FK name if needed
                $table->dropColumn('item_number_id');
            }

            // Drop foreign key and column for qualification_id
            if (Schema::hasColumn('users', 'qualification_id')) {
                $table->dropForeign(['qualification_id']); // adjust FK name if needed
                $table->dropColumn('qualification_id');
            }
        });

        // 2️⃣ Drop qualifications table
        if (Schema::hasTable('qualifications')) {
            Schema::dropIfExists('qualifications');
        }

        // 3️⃣ Drop is_vacant table
        if (Schema::hasTable('is_vacant')) {
            Schema::dropIfExists('is_vacant');
        }

        // 4️⃣ Drop position_qualification table
        if (Schema::hasTable('position_qualification')) {
            Schema::dropIfExists('position_qualification');
        }
    }

    public function down()
    {
        // 1️⃣ Recreate qualifications table
        Schema::create('qualifications', function (Blueprint $table) {
            $table->id();
            $table->string('qualification_name');
            $table->timestamps();
        });

        // 2️⃣ Recreate is_vacant table (adjust columns if needed)
        Schema::create('is_vacant', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('Vacant');
            $table->timestamps();
        });

        // 3️⃣ Recreate position_qualification table (adjust columns if needed)
        Schema::create('position_qualification', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('position_id');
            $table->unsignedBigInteger('qualification_id');
            $table->timestamps();

            // Foreign keys
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade');
            $table->foreign('qualification_id')->references('id')->on('qualifications')->onDelete('cascade');
        });

        // 4️⃣ Restore columns in users table
        Schema::table('users', function (Blueprint $table) {
            // Restore item_number_id column and FK
            $table->unsignedBigInteger('item_number_id')->nullable();
            $table->foreign('item_number_id')
                  ->references('id')
                  ->on('item_numbers')
                  ->onDelete('set null');

            // Restore qualification_id column and FK
            $table->unsignedBigInteger('qualification_id')->nullable();
            $table->foreign('qualification_id')
                  ->references('id')
                  ->on('qualifications')
                  ->onDelete('set null');
        });
    }
};
