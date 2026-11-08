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
            $table->foreignId('partner_id')->nullable()->after('assigned_team')->constrained('partners')->nullOnDelete();
            $table->foreignId('assigned_partner_crew_id')->nullable()->after('partner_id')->constrained('partner_crews')->nullOnDelete();
            $table->string('arrival_window', 80)->nullable()->after('preferred_time_slot');
            $table->timestamp('estimated_arrival_at')->nullable()->after('arrival_window');
            $table->string('partner_job_status', 30)->nullable()->after('status'); // assigned, accepted, on_the_way, in_progress, completed, declined
            $table->text('partner_notes')->nullable()->after('admin_notes');
            $table->json('completion_photos')->nullable()->after('attached_images');
            $table->timestamp('partner_assigned_at')->nullable()->after('accepted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['partner_id']);
            $table->dropForeign(['assigned_partner_crew_id']);
            $table->dropColumn([
                'partner_id',
                'assigned_partner_crew_id',
                'arrival_window',
                'estimated_arrival_at',
                'partner_job_status',
                'partner_notes',
                'completion_photos',
                'partner_assigned_at',
            ]);
        });
    }
};
