<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_insights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('type', 50);
            $table->date('date');
            $table->json('payload');
            $table->timestamps();

            $table->unique(['business_id', 'type', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_insights');
    }
};
