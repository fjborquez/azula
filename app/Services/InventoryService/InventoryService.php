<?php

namespace App\Services\InventoryService;

use App\Contracts\Services\InventoryService\InventoryServiceInterface;
use App\Exceptions\ResourceNotFoundException;
use App\Models\Inventory;
use Google\Cloud\PubSub\PubSubClient;
use Illuminate\Database\Eloquent\Builder;
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

        $this->changeDetailStatus(new Collection($created), 1);

        return $created;
    }

    public function getList(): Collection
    {
        return QueryBuilder::for(Inventory::class)
            ->allowedFilters([
                AllowedFilter::exact('house_id'),
                AllowedFilter::callback('has_active_product_status', function (Builder $query, $value) {
                    $query->whereHas('productStatus', function(Builder $subQuery) {
                        $subQuery->where('is_active', 1);
                    });
                })
            ])->get();
    }

    public function update(int $inventoryId, array $data = []): void
    {
        $inventory = Inventory::find($inventoryId);

        if ($inventory == null) {
            throw new ResourceNotFoundException('Inventory detail not found');
        }

        $inventory->update($data);
    }

    private function changeDetailStatus(Collection $inventory, int $processAction): void
    {
        if (empty($inventory)) {
            return;
        }

        $pubSub = new PubSubClient;
        $topic = $pubSub->topic('product-status-update');
        $topic->publish([
            'data' => json_encode(
                [
                    'process_action' => $processAction,
                    'inventory' => $inventory->toArray(),
                ]),
        ]);
    }

    public function getInventoryDetailsList(): Collection
    {
        return Inventory::with('productStatus')->whereHas('productStatus', function ($query) {
            $query->where('is_final_phase', false);
        })
            ->whereHas('productStatus', function ($query) {
                $query->where('product_status_transitions.is_active', true);
                $query->where(function ($subQuery) {
                    $subQuery->where(function ($condition) {
                        $condition->where('product_status_transitions.product_status_id', 6)
                            ->whereNotNull('inventories.expiration_date');
                    })
                        ->orWhere('product_status_transitions.product_status_id', '!=', 6);
                });
            })
            ->orderBy('id')
            ->get();
    }

    public function processInventoryDetailStatusTransitions(): void
    {
        $this->changeDetailStatus($this->getInventoryDetailsList(), 2);
    }
}
