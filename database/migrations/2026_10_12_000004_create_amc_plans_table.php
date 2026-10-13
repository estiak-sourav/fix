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
        Schema::create('amc_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('badge')->nullable();
            $table->string('tier')->default('standard');
            $table->string('target_audience')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('price_period')->default('Monthly / Billed Annually');
            $table->string('sla_response_time')->default('2h Emergency Response');
            $table->boolean('is_popular')->default(false);
            $table->json('features')->nullable();
            $table->string('button_text')->default('Select Plan');
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amc_plans');
    }
};
