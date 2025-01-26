<?php

namespace App\Contracts\Services\InventoryService;

use App\Models\Inventory;
use Illuminate\Database\Eloquent\Collection;

interface InventoryServiceInterface
{
    public function create(array $data = []): Inventory;

    public function getList(): Collection;

    public function update(int $inventoryId, array $data = []): void;

    public function getInventoryDetailsList(): Collection;

    public function discard(int $inventoryId): void;

    public function consume(int $inventoryId): void;

    public function get(int $inventoryId): Inventory;
}
