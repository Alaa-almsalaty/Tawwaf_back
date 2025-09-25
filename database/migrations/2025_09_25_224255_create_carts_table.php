<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visitor_id')->constrained('users');
            $table->foreignId('package_id')->constrained('packages');
            $table->foreignId('package_room_id')->constrained('package_rooms');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
