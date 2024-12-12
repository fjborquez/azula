<?php

namespace App\Http\Controllers;

use App\Contracts\Services\InventoryService\InventoryServiceInterface;
use App\Exceptions\ResourceNotFoundException;
use App\Http\Requests\InventoryRequest;
use Symfony\Component\HttpFoundation\Response;

class InventoryController extends Controller
{
    private $fields = ['house_id', 'house_description', 'quantity', 'uom_id', 'uom_abbreviation', 'purchase_date', 'expiration_date',
        'catalog_id', 'catalog_description', 'brand_id', 'brand_name', 'category_id', 'category_name'];

    public function __construct(
        private readonly InventoryServiceInterface $inventoryService
    ) {}

    public function store(InventoryRequest $request)
    {
        $validated = $request->safe()->only($this->fields);
        $inventory = $this->inventoryService->create($validated);

        return response()->noContent(Response::HTTP_CREATED)
            ->header('Location', url('/api/user/'.$inventory->id));
    }

    public function list()
    {
        return $this->inventoryService->getList();
    }

    public function update(int $inventoryId, InventoryRequest $request)
    {
        $data = $request->safe()->only($this->fields);

        try {
            $this->inventoryService->update($inventoryId, $data);

            return response()->noContent(Response::HTTP_NO_CONTENT);
        } catch (ResourceNotFoundException $exception) {
            return response()->noContent(Response::HTTP_NOT_FOUND);
        }
    }

    public function listNotFinalPhaseStatus()
    {
        return $this->inventoryService->getInventoryDetailsList();
    }
}
