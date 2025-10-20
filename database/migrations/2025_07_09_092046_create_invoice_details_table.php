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
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onUpdate('cascade')->onDelete('cascade'); // Foreign key to the invoice
            $table->string('item_name'); // Name of the item or service
            $table->string('item_type'); // Type of the item (e.g., product, service)
            $table->string('item_description')->nullable(); // Optional description of the item
            $table->decimal('quantity', 10, 2)->default(1.00); // Quantity of the item, default is 1.00
            $table->decimal('credit_dinar', 10, 2)->default(0.00); // Credit amount in Dinar
            $table->decimal('credit_usd', 10, 2)->default(0.00); // Credit amount in USD
            $table->decimal('debit_dinar', 10, 2)->default(0.00); // Debit amount in Dinar
            $table->decimal('debit_usd', 10, 2)->default(0.00); // Debit amount in USD
            $table->enum('status', ['paid', 'unpaid', 'partially_paid'])
                ->default('unpaid'); // Status of the invoice detail, default is unpaid
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
        Schema::dropIfExists('invoice_details');
    }
};
