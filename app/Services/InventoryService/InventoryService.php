<?php

namespace App\Services\InventoryService;

use App\Contracts\Services\InventoryService\InventoryServiceInterface;
use App\Exceptions\ResourceNotFoundException;
use App\Models\Inventory;
use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class InventoryService implements InventoryServiceInterface
{
    public function create(array $data = []): Inventory
    {
        if (empty($data['purchase_date'])) {
            $data['purchase_date'] = now();
        }

        return Inventory::factory()->create($data);
    }

    public function getList(): Collection
    {
        return QueryBuilder::for(Inventory::class)
            ->allowedFilters(AllowedFilter::exact('house_id'))
            ->get();
    }

    public function update(int $inventoryId, array $data = []): void
    {
        $inventory = Inventory::find($inventoryId);

        if ($inventory == null) {
            throw new ResourceNotFoundException('Inventory detail not found');
        }

        $inventory->update($data);
    }
}
