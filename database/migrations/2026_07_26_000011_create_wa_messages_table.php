<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wa_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('to_number', 30);
            $table->enum('type', ['order_confirmation', 'broadcast', 'auto_reply']);
            $table->text('body');
            $table->enum('status', ['pending', 'sent', 'failed', 'mocked'])->default('pending');
            $table->json('provider_response')->nullable();
            $table->dateTime('sent_at')->nullable();
            $table->timestamps();

            $table->index(['business_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wa_messages');
    }
};
