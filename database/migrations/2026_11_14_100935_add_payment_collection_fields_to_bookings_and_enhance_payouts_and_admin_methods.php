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
        // 1. Create admin_payment_methods table
        if (! Schema::hasTable('admin_payment_methods')) {
            Schema::create('admin_payment_methods', function (Blueprint $table) {
                $table->id();
                $table->string('method_type', 30); // bkash, nagad, rocket, upay, bank, offline
                $table->string('account_number', 60)->nullable();
                $table->string('account_name', 100)->nullable();
                $table->string('number_type', 20)->nullable(); // merchant, personal, agent
                $table->string('bank_name', 100)->nullable();
                $table->string('branch_name', 100)->nullable();
                $table->string('routing_number', 50)->nullable();
                $table->text('instructions')->nullable();
                $table->boolean('is_active')->default(true);
                $table->boolean('is_default')->default(false);
                $table->timestamps();
            });
        }

        // 2. Add payment collection columns to bookings table
        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'payment_collected_by')) {
                $table->string('payment_collected_by', 30)->nullable()->after('payment_status'); // crew, admin, office
            }
            if (! Schema::hasColumn('bookings', 'payment_method')) {
                $table->string('payment_method', 40)->nullable()->after('payment_collected_by'); // cash, offline, bkash, nagad, rocket, bank, other
            }
            if (! Schema::hasColumn('bookings', 'collected_amount')) {
                $table->decimal('collected_amount', 10, 2)->nullable()->after('payment_method');
            }
            if (! Schema::hasColumn('bookings', 'payment_reference')) {
                $table->string('payment_reference', 120)->nullable()->after('collected_amount');
            }
            if (! Schema::hasColumn('bookings', 'payment_collected_at')) {
                $table->timestamp('payment_collected_at')->nullable()->after('payment_reference');
            }
        });

        // 3. Enhance partner_payouts table for two-way settlements
        Schema::table('partner_payouts', function (Blueprint $table) {
            if (! Schema::hasColumn('partner_payouts', 'direction')) {
                $table->string('direction', 30)->default('admin_to_partner')->after('payout_number')->index(); // admin_to_partner, partner_to_admin
            }
            if (! Schema::hasColumn('partner_payouts', 'initiated_by')) {
                $table->string('initiated_by', 20)->default('partner')->after('direction')->index(); // partner, admin
            }
            if (! Schema::hasColumn('partner_payouts', 'admin_payment_method_id')) {
                $table->foreignId('admin_payment_method_id')->nullable()->after('partner_payment_method_id')->constrained('admin_payment_methods')->nullOnDelete();
            }
            if (! Schema::hasColumn('partner_payouts', 'payment_method_type')) {
                $table->string('payment_method_type', 30)->nullable()->after('admin_payment_method_id'); // cash, bkash, nagad, rocket, bank, offline
            }
            if (! Schema::hasColumn('partner_payouts', 'proof_file')) {
                $table->string('proof_file', 255)->nullable()->after('partner_notes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partner_payouts', function (Blueprint $table) {
            $colsToDrop = [];
            if (Schema::hasColumn('partner_payouts', 'proof_file')) {
                $colsToDrop[] = 'proof_file';
            }
            if (Schema::hasColumn('partner_payouts', 'payment_method_type')) {
                $colsToDrop[] = 'payment_method_type';
            }
            if (Schema::hasColumn('partner_payouts', 'admin_payment_method_id')) {
                $table->dropForeign(['admin_payment_method_id']);
                $colsToDrop[] = 'admin_payment_method_id';
            }
            if (Schema::hasColumn('partner_payouts', 'initiated_by')) {
                $colsToDrop[] = 'initiated_by';
            }
            if (Schema::hasColumn('partner_payouts', 'direction')) {
                $colsToDrop[] = 'direction';
            }
            if (! empty($colsToDrop)) {
                $table->dropColumn($colsToDrop);
            }
        });

        Schema::table('bookings', function (Blueprint $table) {
            $colsToDrop = [];
            foreach (['payment_collected_by', 'payment_method', 'collected_amount', 'payment_reference', 'payment_collected_at'] as $col) {
                if (Schema::hasColumn('bookings', $col)) {
                    $colsToDrop[] = $col;
                }
            }
            if (! empty($colsToDrop)) {
                $table->dropColumn($colsToDrop);
            }
        });

        Schema::dropIfExists('admin_payment_methods');
    }
};
