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
            $table->boolean('cancellation_requested')->default(false)->after('contract_status');
            $table->timestamp('cancellation_requested_at')->nullable()->after('cancellation_requested');
            $table->string('cancellation_requested_by', 30)->nullable()->after('cancellation_requested_at');
            $table->text('cancellation_request_reason')->nullable()->after('cancellation_requested_by');
            $table->string('cancellation_review_status', 30)->nullable()->after('cancellation_request_reason');
            $table->text('cancellation_review_notes')->nullable()->after('cancellation_review_status');
            $table->timestamp('cancellation_reviewed_at')->nullable()->after('cancellation_review_notes');
            $table->unsignedBigInteger('cancellation_reviewed_by_admin_id')->nullable()->after('cancellation_reviewed_at');
            $table->text('crew_cancellation_reason')->nullable()->after('cancellation_reviewed_by_admin_id');
            $table->timestamp('crew_cancellation_requested_at')->nullable()->after('crew_cancellation_reason');
        });

        Schema::create('amc_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('partner_id')->nullable()->constrained('partners')->nullOnDelete();
            $table->foreignId('assigned_partner_crew_id')->nullable()->constrained('partner_crews')->nullOnDelete();
            $table->integer('shift_number')->default(1);
            $table->string('title', 255);
            $table->date('scheduled_date');
            $table->string('scheduled_time_slot', 100)->nullable();
            $table->string('arrival_window', 100)->nullable();
            $table->string('status', 30)->default('scheduled'); // scheduled, on_the_way, in_progress, completed, cancelled, skipped
            $table->string('cancelled_by', 30)->nullable(); // admin, crew, customer
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('completion_notes')->nullable();
            $table->text('crew_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index(['booking_id', 'scheduled_date']);
            $table->index(['partner_id', 'status']);
        });

        Schema::create('amc_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->foreignId('partner_id')->nullable()->constrained('partners')->nullOnDelete();
            $table->string('sender_type', 30)->default('customer'); // customer, admin, partner
            $table->string('sender_name', 150);
            $table->text('note');
            $table->boolean('is_customer_visible')->default(true);
            $table->timestamps();

            $table->index(['booking_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amc_notes');
        Schema::dropIfExists('amc_shifts');

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'cancellation_requested',
                'cancellation_requested_at',
                'cancellation_requested_by',
                'cancellation_request_reason',
                'cancellation_review_status',
                'cancellation_review_notes',
                'cancellation_reviewed_at',
                'cancellation_reviewed_by_admin_id',
                'crew_cancellation_reason',
                'crew_cancellation_requested_at',
            ]);
        });
    }
};
