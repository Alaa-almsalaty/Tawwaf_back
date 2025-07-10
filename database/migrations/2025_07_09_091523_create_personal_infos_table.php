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
        Schema::create('personal_infos', function (Blueprint $table) {
            $table->id();
            $table->string('first_name_ar', 50)->nullable();
            $table->string('first_name_en', 50)->nullable();
            $table->string('second_name_ar', 50)->nullable();
            $table->string('second_name_en', 50)->nullable();
            $table->string('grand_father_name_ar', 50)->nullable();
            $table->string('grand_father_name_en', 50)->nullable();
            $table->string('last_name_ar', 50)->nullable();
            $table->string('last_name_en', 50)->nullable();
            $table->date('DOB');// Date of Birth
            $table->enum('family_status', ['single', 'married', 'divorced', 'widowed'])->default('single');
            $table->enum('gender', ['female','male']);
            $table->enum('medical_status', ['healthy', 'sick', 'disabled'])->default('healthy');
            $table->string('phone', 20)->nullable();
            $table->string('note')->nullable(); // Optional note field for additional information
            $table->foreignId('passport_no')->nullable()
                ->constrained('passports')
                ->onUpdate('cascade')
                ->onDelete('cascade'); // Foreign key to the passport, if applicable

            $table->timestamps();
            $table->softDeletes(); // Soft delete support
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_infos');
    }
};
