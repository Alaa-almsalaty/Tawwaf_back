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
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->string('provider_name', 100)->nullable();
            $table->string('provider_type',50); //e.g., 'Visa','Hotel','Airline', etc.
            $table->string('email')->unique()->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('contact_info', 100)->nullable(); // Contact person's name or info
            $table->string('tenant_id');
            $table->boolean('is_deal')->default(false); // Indicates if the provider is a deal or not
            $table->string('address', 255)->nullable();
            $table->string('note')->nullable();
            $table->foreign('tenant_id')->references('id')->on('tenants')->onUpdate('cascade')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
};
