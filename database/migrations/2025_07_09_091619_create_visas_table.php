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
        Schema::create('visas', function (Blueprint $table) {
            $table->id();
            $table->string('visa_number', 50)->unique(); // Unique visa number
            $table->string('visa_type', 50); // Type of visa (e.g. hajj, umrah, tourist)
            $table->date('issue_date'); // Date when the visa was issued
            $table->date('expiry_date'); // Date when the visa expires
            $table->integer('duration_days'); // Duration of the visa in days
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade'); // Foreign key to the client
            $table->foreignId('provider_id')->nullable()->constrained('providers')->onDelete('cascade'); // Foreign key to the provider, if applicable
            $table->enum('state', ['pending', 'approved', 'rejected'])->default('pending'); // State of the visa, default is pending
            $table->string('border_number', 50)->nullable(); // Optional border number for the visa
            $table->string('note')->nullable(); // Optional note field for additional information
            $table->timestamps();
            $table->softDeletes(); // Soft delete support
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visas');
    }
};
