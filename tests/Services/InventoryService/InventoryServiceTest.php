<?php

namespace Tests\Unit\App\Services\InventoryService;

use App\Models\Inventory;
use App\Services\InventoryService\InventoryService;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use PHPUnit\Framework\TestCase;

class InventoryServiceTest extends TestCase
{
    private $inventoryService;

    public function setUp(): void
    {
        parent::setUp();

        // Crear una instancia de InventoryService
        $this->inventoryService = new InventoryService;
    }

    public function tearDown(): void
    {
        Mockery::close();
    }

    public function test_that_get_not_final_phase_inventory_details(): void
    {
        $expectedInventories = new Collection([
            (object) [
                'id' => 6,
                'house_id' => 1,
                'house_description' => 'Pallet Town',
                'quantity' => 1.75,
                'uom_id' => 3,
                'uom_abbreviation' => 'kg',
                'purchase_date' => '2024-08-31',
                'expiration_date' => '2024-11-30',
                'catalog_id' => 10,
                'catalog_description' => 'Beef Steak Block',
                'brand_id' => 2,
                'brand_name' => 'Cecinas Winter',
                'category_id' => 2,
                'category_name' => 'Butchery',
                'created_at' => '2024-09-01 22:51:36',
                'updated_at' => '2024-09-01 23:03:16',
            ],
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

        $inventoryMock = Mockery::mock('alias:'.Inventory::class);

        $inventoryMock->shouldReceive('whereHas')
            ->once()
            ->with('productStatus', Mockery::on(function ($query) {
                $mockQuery = Mockery::mock();
                $mockQuery->shouldReceive('where')->with('is_final_phase', false);
                $query($mockQuery);

                return true;
            }))
            ->andReturnSelf();

        $inventoryMock->shouldReceive('get')
            ->once()
            ->andReturn($expectedInventories);

        $actualInventories = $this->inventoryService->getInventoryDetailsList();
        $this->assertEquals($expectedInventories, $actualInventories);
    }
}
