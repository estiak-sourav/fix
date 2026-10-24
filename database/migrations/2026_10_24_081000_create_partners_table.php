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
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // Applicant Inputs (Filled by applicant on web portal)
            $table->string('company_name');
            $table->string('contact_person');
            $table->string('phone', 30)->index();
            $table->string('email')->nullable();
            $table->string('trade_domain', 100);
            $table->string('technicians_count', 30)->default('1-5');
            $table->text('coverage_areas');

            // Administrative & Enlistment Controls (Admin Input ONLY)
            $table->string('status', 30)->default('pending')->index(); // pending, approved, rejected, suspended
            $table->string('partner_type', 30)->default('individual'); // individual, company
            $table->string('payment_method', 50)->nullable(); // bank, bkash, nagad, rocket
            $table->string('bank_name', 100)->nullable();
            $table->string('account_holder', 100)->nullable();
            $table->string('account_number', 60)->nullable();
            $table->string('branch_name', 100)->nullable();
            $table->string('mfs_number', 30)->nullable();
            $table->decimal('commission_rate', 5, 2)->nullable()->default(15.00); // 15% standard
            $table->string('payout_terms', 50)->default('bi_weekly');
            $table->text('admin_notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by_admin_id')->nullable()->constrained('admins')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
