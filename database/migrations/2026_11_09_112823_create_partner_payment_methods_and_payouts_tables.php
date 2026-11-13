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
        Schema::create('partner_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained('partners')->cascadeOnDelete();
            $table->string('method_type', 30); // bkash, nagad, rocket, bank
            $table->string('account_number', 60);
            $table->string('account_name', 100)->nullable();
            $table->string('number_type', 20)->nullable(); // personal, agent, merchant
            $table->string('bank_name', 100)->nullable();
            $table->string('branch_name', 100)->nullable();
            $table->string('routing_number', 50)->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('partner_payouts', function (Blueprint $table) {
            $table->id();
            $table->string('payout_number', 40)->unique();
            $table->foreignId('partner_id')->constrained('partners')->cascadeOnDelete();
            $table->foreignId('partner_payment_method_id')->nullable()->constrained('partner_payment_methods')->nullOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('status', 30)->default('pending')->index(); // pending, paid, rejected, cancelled
            $table->string('payment_method_details', 255)->nullable();
            $table->string('transaction_reference', 100)->nullable();
            $table->text('admin_notes')->nullable();
            $table->text('partner_notes')->nullable();
            $table->timestamp('requested_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('processed_by_admin_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_payouts');
        Schema::dropIfExists('partner_payment_methods');
    }
};
