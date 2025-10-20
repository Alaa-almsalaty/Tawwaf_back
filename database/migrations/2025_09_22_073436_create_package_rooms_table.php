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
        Schema::create('package_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('packages')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('room_type', ['single', 'double', 'triple', 'quad', 'quintuple', 'sextuple', 'septuple',])->default('single');
            $table->decimal('total_price_dinar')->nullable();
            $table->decimal('total_price_usd')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_rooms');
    }
};
