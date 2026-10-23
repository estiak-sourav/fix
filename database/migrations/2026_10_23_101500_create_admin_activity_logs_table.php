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
        Schema::create('admin_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->string('admin_name')->nullable();
            $table->string('admin_email')->nullable();
            $table->string('admin_role')->nullable();
            $table->string('category', 50)->index(); // auth, staff, bookings, tickets, users, services, settings, content
            $table->string('action', 100)->index();   // e.g. staff.create, booking.status_update, auth.login
            $table->text('description');
            $table->string('subject_type')->nullable()->index();
            $table->string('subject_id')->nullable()->index();
            $table->json('properties')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['created_at', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_activity_logs');
    }
};
