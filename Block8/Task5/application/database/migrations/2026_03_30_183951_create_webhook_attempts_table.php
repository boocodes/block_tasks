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
        Schema::create('webhook_attempts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('attempt');
            $table->string('status');
            $table->string('http_code')->nullable();
            $table->integer('response_time')->nullable();
            $table->string('idempotency_key');
            $table->string('event_type');
            $table->string('entity_id');
            $table->string('error')->nullable();
            $table->unsignedBigInteger('webhook_id');
            $table->integer('max_attempts')->default(3);
            $table->timestamp('scheduled_at');
            $table->timestamp('executed_at')->nullable();
            $table->foreign('webhook_id')->references('id')->on('webhooks');
            
            $table->unique(['idempotency_key', 'event_type', 'webhook_id'], 'webhook_attempts_unieque');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_attempts');
    }
};
