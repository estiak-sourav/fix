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
        $subtitles = [
            'amc_plans' => 'Predictable operational expenditure and scheduled preventive checkups that minimize costly equipment breakdowns.',
            'how_it_works' => 'Getting professional maintenance for your facility is straightforward, transparent, and hassle-free in just 4 simple steps.',
            'why_fix' => 'We combine skilled engineering, vetted vendor networks, and institutional-grade accountability to protect property value and enhance liveability.',
            'who_we_serve' => 'Tailored engineering and facility maintenance protocols across diverse industries and property classes.',
            'testimonials' => 'Hear from building managers, facility heads, and property owners who trust FIX.',
            'contact' => 'Have an urgent facility breakdown or need an on-site engineering audit for your commercial property?',
        ];

        foreach ($subtitles as $key => $text) {
            DB::table('site_contents')
                ->where('section_key', $key)
                ->where('subtitle', $text)
                ->update(['subtitle' => null]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $subtitles = [
            'amc_plans' => 'Predictable operational expenditure and scheduled preventive checkups that minimize costly equipment breakdowns.',
            'how_it_works' => 'Getting professional maintenance for your facility is straightforward, transparent, and hassle-free in just 4 simple steps.',
            'why_fix' => 'We combine skilled engineering, vetted vendor networks, and institutional-grade accountability to protect property value and enhance liveability.',
            'who_we_serve' => 'Tailored engineering and facility maintenance protocols across diverse industries and property classes.',
            'testimonials' => 'Hear from building managers, facility heads, and property owners who trust FIX.',
            'contact' => 'Have an urgent facility breakdown or need an on-site engineering audit for your commercial property?',
        ];

        foreach ($subtitles as $key => $text) {
            DB::table('site_contents')
                ->where('section_key', $key)
                ->whereNull('subtitle')
                ->update(['subtitle' => $text]);
        }
    }
};
