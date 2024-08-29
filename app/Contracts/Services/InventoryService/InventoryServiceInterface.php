<?php

namespace App\Contracts\Services\InventoryService;

use App\Models\Inventory;

interface InventoryServiceInterface
{
    public function create(array $data = []): Inventory;
}
