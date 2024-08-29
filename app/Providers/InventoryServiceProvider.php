<?php

namespace App\Providers;

use App\Contracts\Services\InventoryService\InventoryServiceInterface;
use App\Services\InventoryService\InventoryService;
use Illuminate\Support\ServiceProvider;

class InventoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(
            InventoryServiceInterface::class,
            InventoryService::class
        );
    }
}
