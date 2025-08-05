<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command("products:check-minimum-stock")
    ->dailyAt('02:00')
    ->onFailure(function () {
        Log::error("Failed to check minimum stock levels for products.");
    })
    ->onSuccess(function () {
        Log::info("Successfully checked minimum stock levels for products.");
    });
