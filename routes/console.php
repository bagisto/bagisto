<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

$schedule = app(Schedule::class);

$schedule->command('custom:white-label')->everyMinute();
$schedule->command('user:set-role-from-tier')->everyMinute();