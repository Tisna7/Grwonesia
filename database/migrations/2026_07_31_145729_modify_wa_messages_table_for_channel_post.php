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
        Schema::table('wa_messages', function (Blueprint $table) {
            $table->string('to_number', 100)->change();
            $table->string('type', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wa_messages', function (Blueprint $table) {
            $table->string('to_number', 30)->change();
            $table->enum('type', ['order_confirmation', 'broadcast', 'auto_reply'])->change();
        });
    }
};
