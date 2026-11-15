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
            $table->string('amc_billing_type', 30)->default('monthly')->after('billing_cycle');
            $table->decimal('payout_per_shift', 10, 2)->nullable()->after('recurring_amount');
        });

        Schema::table('amc_shifts', function (Blueprint $table) {
            $table->decimal('shift_cost', 10, 2)->nullable()->after('arrival_window');
            $table->decimal('payout_amount', 10, 2)->nullable()->after('shift_cost');
            $table->string('payout_status', 40)->default('pending')->after('payout_amount'); // pending, pending_client_payment, payable, paid
            $table->string('payment_collected_by', 30)->nullable()->after('payout_status'); // crew, admin, office, online
            $table->decimal('collected_amount', 10, 2)->default(0.00)->after('payment_collected_by');
            $table->string('payment_method', 50)->nullable()->after('collected_amount');
            $table->string('payment_reference', 100)->nullable()->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('amc_shifts', function (Blueprint $table) {
            $table->dropColumn([
                'shift_cost',
                'payout_amount',
                'payout_status',
                'payment_collected_by',
                'collected_amount',
                'payment_method',
                'payment_reference',
            ]);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'amc_billing_type',
                'payout_per_shift',
            ]);
        });
    }
};
