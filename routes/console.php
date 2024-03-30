<?php

use App\Console\Commands\SendEmailBirthday;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('send-email-birthday', function () {
    $this->comment('Running command to send birthday emails...');
    $this->call(SendEmailBirthday::class);
})->daily();

