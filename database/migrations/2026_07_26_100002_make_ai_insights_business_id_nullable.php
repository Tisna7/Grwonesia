<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Insight level regional (Government) tidak terikat satu bisnis.
     */
    public function up(): void
    {
        Schema::table('ai_insights', function (Blueprint $table) {
            $table->dropForeign(['business_id']);
            $table->unsignedBigInteger('business_id')->nullable()->change();
            $table->foreign('business_id')->references('id')->on('businesses')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('ai_insights', function (Blueprint $table) {
            $table->dropForeign(['business_id']);
            $table->unsignedBigInteger('business_id')->nullable(false)->change();
            $table->foreign('business_id')->references('id')->on('businesses')->cascadeOnDelete();
        });
    }
};
