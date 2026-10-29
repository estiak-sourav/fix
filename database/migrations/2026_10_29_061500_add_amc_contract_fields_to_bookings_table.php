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
            $table->date('contract_start_date')->nullable()->after('completed_at');
            $table->date('contract_end_date')->nullable()->after('contract_start_date');
            $table->string('agreement_document_path')->nullable()->after('contract_end_date');
            $table->text('amc_package_notes')->nullable()->after('agreement_document_path');
            $table->string('contract_status', 30)->nullable()->after('amc_package_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'contract_start_date',
                'contract_end_date',
                'agreement_document_path',
                'amc_package_notes',
                'contract_status',
            ]);
        });
    }
};

