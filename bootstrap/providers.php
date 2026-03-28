<?php

use App\Providers\AangServices\HouseServiceProvider;
use App\Providers\AppServiceProvider;
use App\Providers\AzulaServices\InventoryServiceProvider;

return [
    AppServiceProvider::class,
    InventoryServiceProvider::class,
    HouseServiceProvider::class,
];
