<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create(['name' => 'Widget A', 'category_id' => 1, 'unit_price' => 100.00]);
        Product::create(['name' => 'Gadget B', 'category_id' => 2, 'unit_price' => 80.00]);
        Product::create(['name' => 'Flash Deal C', 'category_id' => 1, 'unit_price' => 120.00]);
        Product::create(['name' => 'Intro SKU D', 'category_id' => 2, 'unit_price' => 60.00]);
        Product::create(['name' => 'Legacy Thing E', 'category_id' => 3, 'unit_price' => 50.00]);
    }
}
