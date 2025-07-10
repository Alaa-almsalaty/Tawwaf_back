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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_type'); // Type of ticket (e.g., one-way, round-trip)
            $table->string('ticket_class'); // Class of ticket (e.g., economy, business, first)
            $table->enum('ticket_age', ['adult', 'child', 'infant'])->default('adult'); // Age category of the ticket holder
            $table->decimal('price_dinar', 10, 2)->default(0.00); // Price of the ticket in Dinar
            $table->decimal('price_usd', 10, 2)->default(0.00); // Price of the ticket in USD
            $table->string('season')->nullable(); // Optional field for the season of the ticket
            $table->string('note')->nullable(); // Optional note field for additional information
            $table->foreignId('provider_id')
                ->constrained('providers'); // Foreign key to the provider

            $table->timestamps();
            $table->softDeletes(); // Soft delete support
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
