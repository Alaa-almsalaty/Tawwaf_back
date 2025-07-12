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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_info_id')->nullable()
                ->constrained('personal_infos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->boolean('is_family_master')->default(false); // Indicates if the client is a family master
            $table->date('register_date')->default(now()); // Default to current date
            $table->enum('register_state',['pending','completed'])->default('pending'); // State of the registration, default is pending
            $table->foreignId('MuhramID')->nullable()
                ->constrained('clients')
                ->onUpdate('cascade')
                ->onDelete('cascade'); // Foreign key to the Muhram client
            $table->enum('Muhram_relation', ['father', 'husband', 'brother', 'son', 'other'])->default('other'); // Type of Muhram relationship
            $table->foreignId('branch_id')->constrained('branches')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('family_id')->nullable()
                ->constrained('clients')
                ->onUpdate('cascade')
                ->onDelete('cascade'); // Foreign key to the family client, if applicable
            $table->foreignId('tenant_id')->constrained('tenants')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('clients');
    }
};
