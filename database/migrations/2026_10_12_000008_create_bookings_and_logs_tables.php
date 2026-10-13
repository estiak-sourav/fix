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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number', 50)->unique()->index();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->foreignId('amc_plan_id')->nullable()->constrained('amc_plans')->nullOnDelete();
            $table->string('service_type', 30)->default('on_demand'); // on_demand, amc_contract, emergency
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone', 30);
            $table->string('company_name')->nullable();
            $table->string('property_type', 50)->default('commercial');
            $table->unsignedInteger('estimated_sqft')->nullable();
            $table->string('division', 50)->default('Dhaka');
            $table->string('district', 50)->default('Dhaka');
            $table->string('area', 80);
            $table->text('street_address');
            $table->date('preferred_date');
            $table->string('preferred_time_slot', 50);
            $table->string('urgency', 30)->default('standard'); // emergency_1h, urgent_4h, standard, scheduled
            $table->longText('problem_description');
            $table->json('attached_images')->nullable();
            $table->string('status', 30)->default('pending_review')->index(); // pending_review, accepted, assigned, in_progress, completed, cancelled
            $table->text('admin_notes')->nullable();
            $table->string('assigned_team')->nullable();
            $table->decimal('estimated_cost', 10, 2)->nullable();
            $table->decimal('final_cost', 10, 2)->nullable();
            $table->string('payment_status', 30)->default('pending');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
        });

        Schema::create('booking_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('changed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('from_status', 30);
            $table->string('to_status', 30);
            $table->text('comment')->nullable();
            $table->boolean('is_customer_visible')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_status_logs');
        Schema::dropIfExists('bookings');
    }
};
