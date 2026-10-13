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
        Schema::table('booking_status_logs', function (Blueprint $table) {
            $table->foreignId('changed_by_admin_id')->nullable()->after('changed_by_user_id')->constrained('admins')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_status_logs', function (Blueprint $table) {
            $table->dropForeign(['changed_by_admin_id']);
            $table->dropColumn('changed_by_admin_id');
        });
    }
};
