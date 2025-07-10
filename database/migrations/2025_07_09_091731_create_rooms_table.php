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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['single', 'double', 'triple', 'four', 'five' ,'six', 'suite'])->default('single'); // Type of room
            $table->integer('capacity')->default(1); // Capacity of the room
            $table->decimal('price_dinar', 10, 2)->default(0.00); // Price of the room in Dinar
            $table->decimal('price_usd', 10, 2)->default(0.00); // Price of the room in USD
            $table->string('note')->nullable(); // Optional note field for additional information
            $table->foreignId('hotel_id')->constrained('hotels');

            $table->timestamps();
            $table->softDeletes(); // Soft delete support
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
