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
        Schema::table('districts', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('bn_name')->index();
        });

        Schema::table('thanas', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('bn_name')->index();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->string('partner_payout_status', 20)->default('unpaid')->after('partner_payout_amount')->index();
            $table->timestamp('partner_payout_paid_at')->nullable()->after('partner_payout_status');
            $table->string('partner_payout_method', 30)->nullable()->after('partner_payout_paid_at');
            $table->string('partner_payout_reference', 100)->nullable()->after('partner_payout_method');
            $table->text('partner_payout_notes')->nullable()->after('partner_payout_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'partner_payout_status',
                'partner_payout_paid_at',
                'partner_payout_method',
                'partner_payout_reference',
                'partner_payout_notes',
            ]);
        });

        Schema::table('thanas', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });

        Schema::table('districts', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
