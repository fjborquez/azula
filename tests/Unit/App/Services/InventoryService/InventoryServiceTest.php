<?php

namespace Tests\Unit\App\Services\InventoryService;

use App\Exceptions\OperationNotAllowedException;
use App\Exceptions\ResourceNotFoundException;
use App\Models\Inventory;
use App\Models\ProductStatusTransition;
use App\Services\InventoryService\InventoryService;
use Google\Cloud\PubSub\PubSubClient;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use PHPUnit\Framework\TestCase;
use Spatie\QueryBuilder\QueryBuilder;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;

class InventoryServiceTest extends TestCase
{
    private $inventoryService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->inventoryService = new InventoryService;
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_create_new_inventory_item(): void
    {
        $inventoryMock = Mockery::mock('overload:'.Inventory::class);
        $pubSubMock = Mockery::mock('overload:'.PubSubClient::class);
        $pubSubMock->shouldReceive('topic')->andReturnSelf();
        $pubSubMock->shouldReceive('publish')->andReturnSelf();
        $inventoryMock->shouldReceive('create')->andReturnSelf();
        $response = $this->inventoryService->create([
            'purchase_date' => now(),
        ]);
        assertEquals($response, $inventoryMock);
    }

    public function test_create_should_add_purchase_date_when_it_is_empty(): void
    {
        $inventoryMock = Mockery::mock('overload:'.Inventory::class);
        $pubSubMock = Mockery::mock('overload:'.PubSubClient::class);
        $pubSubMock->shouldReceive('topic')->andReturnSelf();
        $pubSubMock->shouldReceive('publish')->andReturnSelf();
        $inventoryMock->shouldReceive('create')->andReturnSelf();
        $response = $this->inventoryService->create([]);
        assertEquals($response, $inventoryMock);
    }

    public function test_get_list_returns_inventories(): void
    {
        $expectedInventories = new Collection([
            (object) [
                'id' => 7,
                'house_id' => 1,
                'house_description' => 'Pallet Town',
                'quantity' => 2.00,
                'uom_id' => 3,
                'uom_abbreviation' => 'kg',
                'purchase_date' => '2024-08-31',
                'expiration_date' => '2024-11-30',
                'catalog_id' => 14,
                'catalog_description' => 'Spaguetti Linguini',
                'brand_id' => 8,
                'brand_name' => 'Lucchetti',
                'category_id' => 8,
                'category_name' => 'Pasta',
                'created_at' => '2024-09-01 22:59:09',
                'updated_at' => '2024-09-01 23:02:03',
            ],
            (object) [
                'id' => 8,
                'house_id' => 1,
                'house_description' => 'Pallet Town',
                'quantity' => 1.00,
                'uom_id' => 3,
                'uom_abbreviation' => 'kg',
                'purchase_date' => '2024-09-01',
                'expiration_date' => '2024-12-30',
                'catalog_id' => 10,
                'catalog_description' => 'Beef Steak Block',
                'brand_id' => 2,
                'brand_name' => 'Cecinas Winter',
                'category_id' => 2,
                'category_name' => 'Butchery',
                'created_at' => '2024-09-01 23:09:55',
                'updated_at' => '2024-09-01 23:09:55',
            ],
        ]);

        $queryBuilderMock = Mockery::mock('overload:'.QueryBuilder::class);

        $queryBuilderMock->expects('for')
            ->with(Inventory::class)
            ->andReturnSelf();

        $queryBuilderMock->expects('allowedFilters')->andReturnSelf();
        $queryBuilderMock->expects('defaultSorts')->andReturnSelf();
        $queryBuilderMock->expects('allowedIncludes')->andReturnSelf();
        $queryBuilderMock->expects('allowedSorts')->andReturnSelf();

        $queryBuilderMock->expects('get')
            ->andReturns($expectedInventories);

        $actualInventories = $this->inventoryService->getList();

        assertEquals($expectedInventories, $actualInventories);
    }

    public function test_update_should_update_an_inventory(): void
    {
        $inventoryMock = Mockery::mock('overload:'.Inventory::class);
        $pubSubMock = Mockery::mock('overload:'.PubSubClient::class);
        $inventoryMock->shouldReceive('update')->andReturnSelf();
        $inventoryMock->shouldReceive('find')->once()->andReturnSelf();
        $inventoryMock->shouldReceive('productStatus')->andReturn(new Collection);
        $pubSubMock->shouldReceive('topic')->andReturnSelf();
        $pubSubMock->shouldReceive('publish')->andReturnSelf();

        $this->inventoryService->update(1, []);
        $this->expectNotToPerformAssertions();
    }

    public function test_update_should_throw_exception_when_inventory_is_not_found(): void
    {
        $this->expectException(ResourceNotFoundException::class);
        $inventoryMock = Mockery::mock('overload:'.Inventory::class);
        $inventoryMock->shouldReceive('find')->once()->andReturnNull();
        $this->inventoryService->update(1, []);
    }

    public function test_discard_should_discard_an_inventory_item(): void
    {
        $inventoryMock = Mockery::mock('overload:'.Inventory::class);
        $productStatusCollection = new Collection;
        $productStatusTransition = new ProductStatusTransition;
        $pubSubMock = Mockery::mock('overload:'.PubSubClient::class);
        $productStatusTransition->product_status_id = 1;
        $productStatusTransition->is_active = true;
        $inventoryMock->shouldReceive('with')->once()->andReturnSelf();
        $inventoryMock->shouldReceive('find')->once()->andReturnSelf();
        $inventoryMock->shouldReceive('productStatus')->andReturn($productStatusCollection);
        $pubSubMock->shouldReceive('topic')->andReturnSelf();
        $pubSubMock->shouldReceive('publish')->andReturnSelf();
        $productStatusCollection->push($productStatusTransition);
        $this->inventoryService->discard(1);
        $this->expectNotToPerformAssertions();
    }

    public function test_discard_should_throws_exception_when_inventory_is_not_found(): void
    {
        $this->expectException(ResourceNotFoundException::class);
        $inventoryMock = Mockery::mock('overload:'.Inventory::class);
        $inventoryMock->shouldReceive('with')->once()->andReturnSelf();
        $inventoryMock->shouldReceive('find')->once()->andReturnNull();
        $this->inventoryService->discard(1);
    }

    public function test_discard_should_throws_exception_when_inventory_is_already_discarded(): void
    {
        $this->discard_test_by_status(4);
    }

    public function test_discard_should_throws_exception_when_inventory_is_already_consumed(): void
    {
        $this->discard_test_by_status(5);
    }

    private function discard_test_by_status(int $statusId)
    {
        $this->expectException(OperationNotAllowedException::class);
        $inventoryMock = Mockery::mock('overload:'.Inventory::class);
        $productStatusCollection = new Collection;
        $productStatusTransition = new ProductStatusTransition;
        $productStatusTransition->product_status_id = $statusId;
        $productStatusTransition->is_active = true;
        $inventoryMock->shouldReceive('with')->once()->andReturnSelf();
        $inventoryMock->shouldReceive('find')->once()->andReturnSelf();
        $inventoryMock->shouldReceive('productStatus')->andReturn($productStatusCollection);
        $productStatusCollection->push($productStatusTransition);

        $this->inventoryService->discard(1);
    }

    public function test_get_inventory_details_list_should_return_a_collection(): void
    {
        $inventoryMock = Mockery::mock('overload:'.Inventory::class);
        $inventoryMock->shouldReceive('with')->once()->andReturnSelf();
        $inventoryMock->shouldReceive('whereHas')->andReturnSelf();
        $inventoryMock->shouldReceive('orderBy')->andReturnSelf();
        $inventoryMock->shouldReceive('get')->andReturn(new Collection);
        $response = $this->inventoryService->getInventoryDetailsList();
        assertInstanceOf(Collection::class, $response);
    }

    public function test_get_should_return_an_inventory_item_detail(): void
    {
        $inventoryMock = Mockery::mock('overload:'.Inventory::class);
        $inventoryMock->shouldReceive('find')->once()->andReturnSelf();
        $inventoryMock->shouldReceive('with')->andReturnSelf();
        $response = $this->inventoryService->get(1);
        assertInstanceOf(Inventory::class, $response);
    }

    public function test_get_should_throws_an_exception_when_inventory_is_not_found(): void
    {
        $this->expectException(ResourceNotFoundException::class);
        $inventoryMock = Mockery::mock('overload:'.Inventory::class);
        $inventoryMock->shouldReceive('with')->andReturnSelf();
        $inventoryMock->shouldReceive('find')->once()->andReturnNull();
        $this->inventoryService->get(1);
    }

    public function test_consume_should_not_allow_operation_when_inventory_is_consumed(): void
    {
        $inventoryId = 1;
        $productStatusTransitions = new Collection;
        $productStatusTransition = new ProductStatusTransition;
        $productStatusTransition->product_status_id = 5;
        $productStatusTransition->is_active = true;
        $productStatusTransitions->push($productStatusTransition);
        $this->expectException(OperationNotAllowedException::class);
        $inventoryMock = Mockery::mock('overload:'.Inventory::class);
        $inventoryMock->shouldReceive('with')->andReturnSelf();
        $inventoryMock->shouldReceive('find')->andReturnSelf();
        $inventoryMock->shouldReceive('productStatus')->andReturn($productStatusTransitions);
        $this->inventoryService->consume($inventoryId);
    }

    public function test_consume_should_not_find_resource_when_inventory_is_null(): void
    {
        $inventoryId = 1;
        $this->expectException(ResourceNotFoundException::class);
        $inventoryMock = Mockery::mock('overload:'.Inventory::class);
        $inventoryMock->shouldReceive('with')->andReturnSelf();
        $inventoryMock->shouldReceive('find')->andReturnNull();
        $this->inventoryService->consume($inventoryId);
    }

    public function test_consume_should_publish_consumed_inventory(): void
    {
        $inventoryId = 1;
        $productStatusTransitions = new Collection;
        $productStatusTransition = new ProductStatusTransition;
        $productStatusTransition->product_status_id = 1;
        $productStatusTransition->is_active = true;
        $productStatusTransitions->push($productStatusTransition);
        $inventoryMock = Mockery::mock('overload:'.Inventory::class);
        $pubSubClientMock = Mockery::mock('overload:'.PubSubClient::class);
        $inventoryMock->shouldReceive('with')->andReturnSelf();
        $inventoryMock->shouldReceive('find')->andReturnSelf();
        $inventoryMock->shouldReceive('productStatus')->andReturn($productStatusTransitions);
        $pubSubClientMock->shouldReceive('topic')->andReturnSelf();
        $pubSubClientMock->shouldReceive('publish')->andReturnSelf();
        $this->inventoryService->consume($inventoryId);
        $this->expectNotToPerformAssertions();
    }
}
