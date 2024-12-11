<?php

namespace Tests\Unit\App\Services\InventoryService;

use App\Models\Inventory;
use App\Services\InventoryService\InventoryService;
use function PHPUnit\Framework\assertEquals;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use PHPUnit\Framework\TestCase;

use Spatie\QueryBuilder\QueryBuilder;

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
}
