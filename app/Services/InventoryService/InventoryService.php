<?php

namespace App\Services\InventoryService;

use App\Contracts\Services\InventoryService\InventoryServiceInterface;
use App\Models\Inventory;

class InventoryService implements InventoryServiceInterface
{
    public function create(array $data = []): Inventory
    {
        if (empty($data['purchase_date'])) {
            $data['purchase_date'] = now();
        }

        return Inventory::factory()->create($data);
    }
}
