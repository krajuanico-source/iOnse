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
    Schema::table('users', function (Blueprint $table) {
        $table->date('birthday')->nullable()->after('extension_name');
        $table->string('place_of_birth')->nullable()->after('birthday');
        $table->string('civil_status')->nullable()->after('gender');
        $table->string('height')->nullable()->after('civil_status');
        $table->string('weight')->nullable()->after('height');
        $table->string('blood_type')->nullable()->after('weight');
        $table->string('tel_no')->nullable()->after('blood_type');
        $table->string('mobile_no')->nullable()->after('tel_no');
        $table->string('citizenship')->nullable()->after('mobile_no');

        // Permanent Address
        $table->string('perm_country')->nullable();
        $table->string('perm_region')->nullable();
        $table->string('perm_province')->nullable();
        $table->string('perm_city')->nullable();
        $table->string('perm_barangay')->nullable();
        $table->string('perm_street')->nullable();
        $table->string('perm_house_no')->nullable();
        $table->string('perm_village')->nullable();
        $table->string('perm_zipcode')->nullable();

        // Residence Address
        $table->string('res_country')->nullable();
        $table->string('res_region')->nullable();
        $table->string('res_province')->nullable();
        $table->string('res_city')->nullable();
        $table->string('res_barangay')->nullable();
        $table->string('res_street')->nullable();
        $table->string('res_house_no')->nullable();
        $table->string('res_village')->nullable();
        $table->string('res_zipcode')->nullable();
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn([
            'birthday', 'place_of_birth', 'civil_status', 'height', 'weight', 'blood_type',
            'tel_no', 'mobile_no', 'citizenship',
            'perm_country', 'perm_region', 'perm_province', 'perm_city', 'perm_barangay',
            'perm_street', 'perm_house_no', 'perm_village', 'perm_zipcode',
            'res_country', 'res_region', 'res_province', 'res_city', 'res_barangay',
            'res_street', 'res_house_no', 'res_village', 'res_zipcode',
        ]);
    });
}

};
