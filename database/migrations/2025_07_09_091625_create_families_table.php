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
        Schema::create('families', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('family_master_id')
                ->constrained('clients')
                ->onUpdate('cascade')
                ->onDelete('cascade'); // Foreign key to the family master client
            $table->integer('family_size')->default(1); // Number of members in the family
            $table->string('family_name_ar', 100)->nullable(); // Family name in Arabic
            $table->string('family_name_en', 100)->nullable(); // Family name in English
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
        Schema::dropIfExists('families');
    }
};
