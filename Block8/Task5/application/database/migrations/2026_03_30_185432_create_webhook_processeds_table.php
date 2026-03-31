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
        Schema::create('webhook_processeds', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('webhook_id');
            $table->unsignedBigInteger('webhook_attempt_id');
            $table->foreign('webhook_id')->references('id')->on('webhooks');
            $table->foreign('webhook_attempt_id')->references('id')->on('webhook_attempts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_processeds');
    }
};
