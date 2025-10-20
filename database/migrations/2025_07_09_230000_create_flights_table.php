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
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained('trips'); // Foreign key to the trip
            $table->foreignId('airline_id')->constrained('airlines'); // Foreign key to the airline
            $table->dateTime('departure_time'); // Departure time of the flight
            $table->dateTime('arrival_time'); // Arrival time of the flight
            $table->string('departure_airport'); // Departure airport
            $table->string('arrival_airport'); // Arrival airport
            $table->integer('Y_capacity')->default(0); // Economy class capacity
            $table->integer('C_capacity')->default(0); // Business class capacity
            $table->decimal('price_dinar', 10, 2)->default(0.00); // Price of the flight in Dinar
            $table->decimal('price_usd', 10, 2)->default(0.00); // Price of the flight in USD
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
        Schema::dropIfExists('flights');
    }
};
