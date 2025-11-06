<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('other_information', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');

            $table->string('related_within_third_degree')->nullable();
            $table->string('related_within_fourth_degree')->nullable();
            $table->text('related_within_fourth_degree_details')->nullable();

            $table->string('found_guilty_admin_offense')->nullable();
            $table->text('administrative_offense_details')->nullable();

            $table->string('criminally_charged')->nullable();
            $table->date('criminal_date_filed')->nullable();
            $table->text('criminal_status')->nullable();

            $table->string('convicted_of_crime')->nullable();
            $table->text('crime_details')->nullable();

            $table->string('separated_from_service')->nullable();
            $table->text('service_separation_details')->nullable();

            $table->string('candidate_in_election')->nullable();
            $table->text('candidate_in_election_details')->nullable();

            $table->string('resigned_before_election')->nullable();
            $table->text('resigned_before_election_details')->nullable();

            $table->string('immigrant_status')->nullable();
            $table->string('immigrant_country')->nullable();

            $table->string('member_of_indigenous_group')->nullable();
            $table->string('indigenous_group_details')->nullable();

            $table->string('person_with_disability')->nullable();
            $table->string('disability_details')->nullable();

            $table->string('solo_parent')->nullable();
            $table->string('solo_parent_details')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('other_information');
    }
};
