<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (DB::table('product_status')->count() == 0) {
            DB::table('product_status')->insert([
                ['id' => 1, 'description' => 'Fresh'],
                ['id' => 2, 'description' => 'Approaching Expiry'],
                ['id' => 3, 'description' => 'Expired'],
                ['id' => 4, 'description' => 'Consumed'],
                ['id' => 5, 'description' => 'Discarded'],
            ]);
        }
    }
}
