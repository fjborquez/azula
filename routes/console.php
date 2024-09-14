<?php

use App\Services\InventoryService\InventoryService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
    $inventoryService = app(InventoryService::class);
    $inventoryService->processInventoryDetailStatusTransitions();
})->daily()->at('00:00');
