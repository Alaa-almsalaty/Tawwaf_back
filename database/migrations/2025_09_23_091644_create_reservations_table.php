<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('client_id')->nullable()->constrained('clients')->onUpdate('cascade')->onDelete('cascade'); // Foreign key to the client
            $table->foreignId('visitor_id')->nullable()->constrained('users');
            $table->foreignId('family_id')->nullable()->constrained('families');
            $table->foreignId('package_id')->nullable()->constrained('packages')->onUpdate('cascade')->onDelete('cascade'); // Foreign key to the package, if applicable
            $table->foreignId('package_room_id')->nullable()
            ->constrained('package_rooms')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreignId('ticket_id')->nullable()
                ->constrained('tickets')
                ->onUpdate('cascade')
                ->onDelete('cascade'); // Foreign key to the ticket, if applicable
            $table->integer('number_of_travelers')->default(1); // Number of travelers for the reservation
            $table->boolean('has_transportation')->default(false); // Indicates if the reservation includes transportation
            $table->boolean('has_ticket')->default(false); // Indicates if the reservation includes ticket service
            $table->string('extra_services', 255)->nullable(); // Optional field for extra services, if any
            $table->foreignId('created_by')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade'); // User who created the reservation
            $table->date('reservation_date')->default(now()); // Default to current date
            $table->enum('reservation_state', ['sent', 'delivered', 'pending', 'confirmed', 'cancelled', 'completed'])->default('pending'); // State of the reservation, default is pending
            $table->string('note')->nullable(); // Optional note field for additional information
            $table->softDeletes();
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
