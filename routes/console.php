<?php

use App\Services\InventoryService\InventoryService;
use App\Services\MailService\MailService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
    $inventoryService = app(InventoryService::class);
    $inventoryService->processInventoryDetailStatusTransitions();
})->dailyAt('00:00');

Schedule::call(function () {
    $mailService = app(MailService::class);
    $mailService->send();
})->everyMinute();
