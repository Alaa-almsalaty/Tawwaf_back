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
        Schema::create('airlines', function (Blueprint $table) {
            $table->id();
            $table->string('airline_name')->unique(); // Unique name of the airline
            $table->string('airline_type');
            $table->integer('capacity')->default(0); // Capacity of the airline
            $table->decimal('price_dinar', 10, 2)->default(0.00); // Price in Dinar
            $table->foreignId('provider_id')->constrained('providers'); // Foreign key to the provider
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
        Schema::dropIfExists('airlines');
    }
};
