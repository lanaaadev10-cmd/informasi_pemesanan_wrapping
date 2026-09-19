<?php

use App\Console\Commands\SendBookingReminders;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Kirim reminder H-1 booking setiap hari pukul 18:00 (Asia/Jakarta)
Schedule::command(SendBookingReminders::class)->dailyAt('18:00')->timezone('Asia/Jakarta');
