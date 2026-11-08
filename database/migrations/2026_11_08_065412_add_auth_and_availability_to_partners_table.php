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
            $table->string('password')->nullable()->after('phone');
            $table->rememberToken()->after('password');
            $table->boolean('is_currently_offline')->default(false)->after('status');
            $table->timestamp('offline_until')->nullable()->after('is_currently_offline');
            $table->string('offline_reason')->nullable()->after('offline_until');
            $table->json('notification_preferences')->nullable()->after('admin_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->dropColumn([
                'password',
                'remember_token',
                'is_currently_offline',
                'offline_until',
                'offline_reason',
                'notification_preferences',
            ]);
        });
    }
};
