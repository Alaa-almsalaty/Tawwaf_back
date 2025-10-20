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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->decimal('credit_dinar', 10, 2)->default(0.00); // Credit amount in Dinar
            $table->decimal('credit_usd', 10, 2)->default(0.00); // Credit amount in USD
            $table->decimal('debit_dinar', 10, 2)->default(0.00); // Debit amount in Dinar
            $table->decimal('debit_usd', 10, 2)->default(0.00); // Debit amount in USD
            $table->enum('status', ['paid', 'unpaid', 'partially_paid'])
                ->default('unpaid'); // Status of the invoice, default is unpaid
            $table->decimal('balance', 20, 2)->default(0.00); // Balance amount, default is 0.00
            $table->string('note')->nullable(); // Optional note field for additional information

            // Foreign keys
            $table->string('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('provider_id')->constrained('providers'); // Foreign key to the provider
            $table->timestamps();
            $table->softDeletes(); // Soft delete support
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
