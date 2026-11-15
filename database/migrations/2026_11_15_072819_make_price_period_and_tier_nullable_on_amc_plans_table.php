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
        Schema::table('amc_plans', function (Blueprint $table) {
            $table->string('price_period')->nullable()->change();
            $table->string('tier')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('amc_plans', function (Blueprint $table) {
            $table->string('price_period')->nullable(false)->default('Monthly / Billed Annually')->change();
            $table->string('tier')->nullable(false)->default('standard')->change();
        });
    }
};
