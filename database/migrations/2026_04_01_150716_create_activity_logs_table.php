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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // What entity is affected
            $table->string('module'); // e.g., 'cheque'
            $table->unsignedBigInteger('module_id'); // cheque id

            // Action details
            $table->string('action');
            // examples: created, updated, deleted, deposited, cleared, bounced

            // User who performed action
            $table->unsignedBigInteger('user_id')->nullable();

            // Change tracking (VERY IMPORTANT)
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            // Description (human-readable)
            $table->text('description')->nullable();

            // Extra metadata
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();

            // Status tracking (optional but useful)
            $table->string('status')->nullable();

            $table->timestamps();

            // Indexing (important for performance)
            $table->index(['module', 'module_id']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
