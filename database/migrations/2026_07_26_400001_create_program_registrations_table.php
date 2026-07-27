<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Pendaftaran UMKM ke program/pelatihan pemerintah (feedback loop Gov ↔ Business).
     */
    public function up(): void
    {
        Schema::create('program_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gov_program_id')->constrained()->cascadeOnDelete();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['gov_program_id', 'business_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_registrations');
    }
};
