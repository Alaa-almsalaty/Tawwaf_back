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
        Schema::create('passports', function (Blueprint $table) {
            $table->id();
            $table->string('passport_number', 20)->unique();
            $table->string('passport_type', 50)->default('ordinary'); // e.g., 'ordinary', 'diplomatic', 'service'
            $table->string('nationality', 50); // e.g., 'libyan', or other nationalities
            $table->date('issue_date'); // Date when the passport was issued
            $table->date('expiry_date'); // Date when the passport expires
            $table->string('Issue_place', 100); // Place where the passport was issued
            $table->string('birth_place',100); // Place of birth, e.g., 'Tripoli', 'Benghazi', etc.
            $table->string('issue_authority', 100)->nullable(); // Authority that issued the passport, e.g., 'Libyan Passport Authority'
            $table->string('passport_img'); // Path to the passport photo
            $table->timestamps();
            $table->softDeletes(); // Soft delete support
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passports');
    }
};
