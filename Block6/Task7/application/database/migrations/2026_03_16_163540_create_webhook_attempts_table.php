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
            $table->integer('attempt_number');
            $table->integer('status_code')->nullable();
            $table->text('response')->nullable();
            $table->string('status');
            $table->unsignedBigInteger('taskId');

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
