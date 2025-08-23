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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('hotel_name', 100)->unique(); // Unique hotel name
            $table->string('manager_name', 100)->nullable(); // Name of the hotel manager
            $table->string('address', 255)->nullable(); // Address of the hotel
            $table->string('city', 100); // City where the hotel is located
            $table->string('phone', 20)->nullable(); // Phone number of the hotel
            $table->integer('capacity');
            $table->integer('rooms_count');// Number of rooms in the hotel
            $table->enum('stars', ['one', 'two', 'three', 'four', 'five' , 'six' , 'seven'])->default('one'); // Hotel rating in stars, default is one star
            $table->decimal('distance_from_center', 8, 2)->default(0.00); // Distance from the city center in meters
            $table->string('note')->nullable(); // Optional note field for additional information
            $table->foreignId('provider_id')->nullable()->constrained('providers'); // Foreign key to the provider

            $table->timestamps();
            $table->softDeletes(); // Soft delete for the hotel
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
