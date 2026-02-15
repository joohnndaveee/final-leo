<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule subscription management tasks
Schedule::command('subscriptions:check-expired')->daily();
Schedule::command('subscriptions:send-reminders')->dailyAt('09:00');
