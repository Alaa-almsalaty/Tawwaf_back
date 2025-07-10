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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('clients')->onUpdate('cascade')->onDelete('cascade'); // Foreign key to the client
            $table->foreignId('family_id')->nullable()->constrained('families');
            $table->foreignId('package_id')->nullable()->constrained('packages')->onUpdate('cascade')->onDelete('cascade'); // Foreign key to the package, if applicable
            $table->foreignId('ticket_id')->nullable()
                ->constrained('tickets')
                ->onUpdate('cascade')
                ->onDelete('cascade'); // Foreign key to the ticket, if applicable
            $table->boolean('has_transportation')->default(false); // Indicates if the reservation includes transportation
            $table->string('extra_services', 255)->nullable(); // Optional field for extra services, if any
            $table->foreignId('created_by')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade'); // User who created the reservation
            $table->date('reservation_date')->default(now()); // Default to current date
            $table->enum('reservation_state', ['pending', 'confirmed', 'cancelled'])->default('pending'); // State of the reservation, default is pending
            $table->string('note')->nullable(); // Optional note field for additional information

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
