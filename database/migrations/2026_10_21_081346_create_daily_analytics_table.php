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
        Schema::create('daily_analytics', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique()->index();
            $table->unsignedInteger('total_visitors')->default(0);
            $table->unsignedInteger('service_visitors')->default(0);
            $table->unsignedInteger('amc_visitors')->default(0);
            $table->unsignedInteger('logins_count')->default(0);
            $table->unsignedInteger('registrations_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_analytics');
    }
};
