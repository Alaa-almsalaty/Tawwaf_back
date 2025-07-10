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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')
                ->constrained('reservations')
                ->onUpdate('cascade')
                ->onDelete('cascade'); // Foreign key to the reservation

            $table->decimal('total_dinar');
            $table->decimal('total_usd')->nullable(); // Optional field for total in USD
            $table->decimal('discount_dinar')->default(0.00); // Decimal value for the total in Dinar
            $table->decimal('discount_usd')->default(0.00); // Decimal value for the total in USD
            $table->enum('status', ['paid', 'unpaid', 'partially_paid'])
                ->default('unpaid'); // Status of the bill, default is unpaid
            $table->decimal('paid_amount', 20, 2)->default(0.00); // Amount that has been paid
            $table->string('note')->nullable(); // Optional note field

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users');
            $table->timestamps();
            $table->softDeletes(); // Soft delete for the bill
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
