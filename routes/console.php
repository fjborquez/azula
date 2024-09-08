<?php

use App\Services\InventoryService\InventoryService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
    $inventoryService = app(InventoryService::class);
    $inventoryService->processInventoryDetailStatusTransitions();
    $this->info('Inventory detail status transitions processed successfully.');
})->daily()->at('00:00');
