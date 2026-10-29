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
            ->where('section_key', 'services')
            ->where('subtitle', 'Comprehensive engineering and facility maintenance solutions delivered across Bangladesh.')
            ->update(['subtitle' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('site_contents')
            ->where('section_key', 'services')
            ->whereNull('subtitle')
            ->update(['subtitle' => 'Comprehensive engineering and facility maintenance solutions delivered across Bangladesh.']);
    }
};
