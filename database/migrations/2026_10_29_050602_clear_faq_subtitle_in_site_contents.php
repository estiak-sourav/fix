<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('site_contents')
            ->where('section_key', 'faq')
            ->where('subtitle', 'Common questions regarding service booking, emergency dispatch, AMC contracts, and billing.')
            ->update(['subtitle' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('site_contents')
            ->where('section_key', 'faq')
            ->whereNull('subtitle')
            ->update(['subtitle' => 'Common questions regarding service booking, emergency dispatch, AMC contracts, and billing.']);
    }
};
