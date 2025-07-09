<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('import_logs', function (Blueprint $table) {
            $table->id();
            $table->string('filename')->unique(); // To prevent duplicates
            $table->string('status')->nullable(); // e.g. Imported, Failed
            $table->timestamp('imported_at')->nullable(); // Optional timestamp of import
            $table->timestamps(); // created_at and updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_logs');
    }
};
