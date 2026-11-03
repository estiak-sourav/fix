<?php

use App\Models\AdminActivityLog;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Automatically prune models daily (e.g. Admin activity logs older than 60 days)
Schedule::command('model:prune', [
    '--model' => [AdminActivityLog::class],
])->daily();
