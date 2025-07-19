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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable();
            $table->string('address', 255);
            $table->string('city', 100);
            $table->string('email')->unique()->nullable();
            $table->string('subscription_status')->nullable();
            $table->boolean('active')->default(true);
            $table->decimal('Balance', 10, 2)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('manager_name', 100)->nullable();
            $table->string('note')->nullable();
            $table->string('tenant_id')->nullable();
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
        Schema::dropIfExists('branches');
    }
};
