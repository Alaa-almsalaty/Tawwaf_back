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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('package_name', 100)->nullable();
            $table->string('package_type', 50); // e.g., 'Basic', 'Premium'
            $table->string('description')->nullable(); // Optional description of the package
            $table->string('features')->nullable(); // Optional features of the package
            $table->date('start_date')->nullable(); // Optional start date for the package
            $table->date('end_date')->nullable(); // Optional end date for the package
            $table->integer('MKduration');
            $table->integer('MDduration');
           // $table->decimal('total_price_dinar')->nullable(); // Optional total price in Dinar
            //$table->decimal('total_price_usd')->nullable(); // Optional total price in USD
            $table->enum('currency', ['dinar', 'usd'])->default('dinar'); // Currency type, default is Dinar
            $table->enum('season',['Umrah','Hajj','Ramadan','Eid','Normal'])->default('Normal'); // Season type, default is Normal
            $table->boolean('status')->default(true); // Status of the package, default is active
            //$table->enum('status', ['active', 'inactive'])->default('active'); // Status of the package, default is active
            $table->string('image')->nullable(); // Optional image URL or path
            $table->string('note')->nullable();
            $table->string('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('MKHotel')->nullable()->constrained('hotels')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('MDHotel')->nullable()->constrained('hotels')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
