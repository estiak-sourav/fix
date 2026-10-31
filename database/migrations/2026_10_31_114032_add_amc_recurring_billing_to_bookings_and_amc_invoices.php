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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('billing_cycle', 50)->default('monthly')->after('contract_status');
            $table->decimal('recurring_amount', 12, 2)->nullable()->after('billing_cycle');
            $table->unsignedTinyInteger('billing_day')->nullable()->default(1)->after('recurring_amount');
            $table->date('next_billing_date')->nullable()->after('billing_day');
        });

        Schema::create('amc_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->string('invoice_number')->unique();
            $table->string('billing_cycle_label')->nullable();
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->date('due_date')->nullable();
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('status', 30)->default('unpaid'); // unpaid, paid, overdue
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_method')->nullable(); // bKash, Bank Transfer, Cash, Card, Cheque
            $table->string('payment_reference')->nullable();
            $table->foreignId('collected_by_admin_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['booking_id', 'status']);
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amc_invoices');

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'billing_cycle',
                'recurring_amount',
                'billing_day',
                'next_billing_date',
            ]);
        });
    }
};
