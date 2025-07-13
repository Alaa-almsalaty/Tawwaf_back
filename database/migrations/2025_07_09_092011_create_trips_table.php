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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->string('departure_place'); // Place of departure
            $table->string('arrival_place'); // Place of arrival
            $table->date('departure_date'); // Date of departure
            $table->date('arrival_date'); // Date of arrival
            $table->integer('Y_capacity');
            $table->integer('C_capacity');
            $table->string('season');
            $table->string('description')->nullable(); // Optional description field for additional information
            $table->string('note')->nullable(); // Optional note field for additional information
            $table->string('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes(); // Soft delete support
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
