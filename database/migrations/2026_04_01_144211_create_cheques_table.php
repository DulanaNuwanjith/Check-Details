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
        Schema::create('cheques', static function (Blueprint $table) {
            $table->id();

            // Core cheque details
            $table->string('cheque_no')->unique()->index();
            $table->date('cheque_date'); // Date written on check
            $table->date('cheque_exp_date')->nullable();
            $table->decimal('cheque_amount', 15, 2);

            // Bank details
            $table->unsignedBigInteger('bank_account_id')->nullable()->index(); // FK to bank_accounts
            $table->string('bank_name');
            $table->string('branch_name')->nullable();
            $table->string('account_no')->nullable();

            // Type & classification
            $table->enum('cheque_type', ['received', 'issued']); // inbound or outbound
            $table->enum('status', [
                'pending',
                'deposited',
                'cleared',
                'bounced',
                'cancelled'
            ])->default('pending')->index();

            // Important dates
            $table->date('deposit_date')->nullable();
            $table->date('realization_date')->nullable(); // when cleared
            $table->date('bounce_date')->nullable();

            // Relationships
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('invoice_id')->nullable();

            // Operational tracking
            $table->unsignedBigInteger('received_by')->nullable(); // user id
            $table->unsignedBigInteger('deposited_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();

            // Financial adjustments
            $table->decimal('bank_charges', 10, 2)->default(0);
            $table->decimal('penalty_amount', 10, 2)->default(0);

            // Extra info
            $table->text('remarks')->nullable();
            $table->string('reference_no')->nullable(); // internal ref

            // Softly delete audit safety
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cheques');
    }
};
