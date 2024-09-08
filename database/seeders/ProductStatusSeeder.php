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
                ['id' => 1, 'description' => 'Fresh', 'is_final_phase' => false],
                ['id' => 2, 'description' => 'Approaching Expiry', 'is_final_phase' => false],
                ['id' => 3, 'description' => 'Expired', 'is_final_phase' => false],
                ['id' => 4, 'description' => 'Consumed', 'is_final_phase' => true],
                ['id' => 5, 'description' => 'Discarded', 'is_final_phase' => true],
            ]);
        }

        if (DB::table('product_status')->count() == 5) {
            DB::table('product_status')->insert([
                ['id' => 6, 'description' => 'Undefined', 'is_final_phase' => false]
            ]);
        }
    }
}
