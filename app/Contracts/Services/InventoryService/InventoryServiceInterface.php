<?php

namespace App\Contracts\Services\InventoryService;

use App\Models\Inventory;
use Illuminate\Database\Eloquent\Collection;

interface InventoryServiceInterface
{
    public function create(array $data = []): Inventory;

    public function getList(): Collection;
}
