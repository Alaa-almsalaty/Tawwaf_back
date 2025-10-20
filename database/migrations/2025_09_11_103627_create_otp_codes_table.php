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
        Schema::create('otp_codes', function (Blueprint $table) {
            $table->id();
             $table->string('tenant_id')->nullable();            // for multi-tenancy (Stancl Tenancy)
            $table->morphs('notifiable');                       // notifiable_type, notifiable_id
            $table->string('reason')->default('login');         // login, reset_password, etc.
            $table->string('target');                           // phone (E.164) or email
            $table->string('primary_channel');                  // 'whatsapp'
            $table->string('fallback_channel')->nullable();     // 'email'
            $table->string('provider_message_id')->nullable();  // WhatsApp message id
            $table->string('code_hash', 128);                   // NEVER store raw OTP
            $table->timestamp('expires_at');
            $table->timestamp('consumed_at')->nullable();
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->timestamps();

            $table->index(['notifiable_type','notifiable_id','reason']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otp_codes');
    }
};
