<?php

namespace App\Services\InventoryService;

use App\Contracts\Services\InventoryService\InventoryServiceInterface;
use App\Exceptions\ResourceNotFoundException;
use App\Models\Inventory;
use Google\Cloud\PubSub\PubSubClient;
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

        $created = Inventory::factory()->create($data);

        $this->changeDetailStatus(new Collection($created));

        return $created;
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

    private function changeDetailStatus(Collection $inventory): void
    {
        $pubSub = new PubSubClient;
        $topic = $pubSub->topic('product-status-update');
        $topic->publish([
            'data' => json_encode(
                [
                    'text' => 'Product status updated',
                    'inventory' => $inventory,
                ]),
        ]);
    }

    public function getInventoryDetailsList(): Collection
    {
        return Inventory::whereHas('productStatus', function ($query) {
            $query->where('is_final_phase', false);
        })->get();
    }
}
