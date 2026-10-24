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
            $table->string('call_status', 50)->nullable()->after('payment_status');
            $table->text('call_notes')->nullable()->after('call_status');
            $table->text('completion_feedback')->nullable()->after('call_notes');
            $table->unsignedTinyInteger('review_rating')->nullable()->after('completion_feedback');
            $table->text('review_comment')->nullable()->after('review_rating');
            $table->timestamp('reviewed_at')->nullable()->after('review_comment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'call_status',
                'call_notes',
                'completion_feedback',
                'review_rating',
                'review_comment',
                'reviewed_at',
            ]);
        });
    }
};
