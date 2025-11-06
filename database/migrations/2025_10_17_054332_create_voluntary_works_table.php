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
        Schema::create('voluntary_works', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // to link to the user
            $table->string('organization_name');
            $table->date('date_from');
            $table->date('date_to')->nullable();
            $table->integer('number_of_hours')->nullable();
            $table->string('position_nature_of_work')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('voluntary_works');
    }
};
