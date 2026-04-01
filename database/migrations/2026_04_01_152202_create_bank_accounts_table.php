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
        Schema::create('bank_accounts', static function (Blueprint $table) {
            $table->id();

            // Bank Info
            $table->string('bank_name');
            $table->string('branch_name')->nullable();
            $table->string('bank_code')->nullable(); // optional

            // Account Info
            $table->string('account_name'); // Account holder name
            $table->string('account_number')->unique();

            // Classification
            $table->enum('account_type', ['savings', 'current', 'business'])->default('current');
            $table->string('currency', 10)->default('LKR');

            // Financial Tracking (optional but useful)
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->decimal('current_balance', 15, 2)->default(0);

            // Status
            $table->boolean('is_active')->default(true);

            // Metadata
            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};
