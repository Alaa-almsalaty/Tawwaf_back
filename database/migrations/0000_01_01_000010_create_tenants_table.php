<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->string('id')->primary(); // Use string ID for tenant
            /*
            $table->string('company_name', 100)->unique();
            $table->string('address', 255)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('email')->unique();
            $table->enum('status', ['active', 'inactive','trial','free'])->default('active');
            $table->string('manager_name', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->decimal('balance', 20)->default('0.00');
            $table->string('note')->nullable();
            $table->string('logo')->nullable();
            $table->string('created_by')->nullable();*/
            //$table->json('data');
            $table->timestamps();
            $table->softDeletes();
            $table->{DB::getDriverName() === 'sqlite' ? 'text' : 'json'}('data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
}
