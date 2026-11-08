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
        Schema::table('partners', function (Blueprint $table) {
            if (! Schema::hasColumn('partners', 'earning_model')) {
                $table->string('earning_model', 30)->default('commission')->after('commission_rate');
            }
            if (! Schema::hasColumn('partners', 'payout_rate')) {
                $table->decimal('payout_rate', 10, 2)->nullable()->after('earning_model');
            }
        });

        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'partner_payout_amount')) {
                $table->decimal('partner_payout_amount', 10, 2)->nullable()->after('partner_notes');
            }
            if (! Schema::hasColumn('bookings', 'partner_worked_hours')) {
                $table->decimal('partner_worked_hours', 5, 2)->nullable()->after('partner_payout_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->dropColumn(['earning_model', 'payout_rate']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['partner_payout_amount', 'partner_worked_hours']);
        });
    }
};
