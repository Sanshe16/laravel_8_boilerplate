<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductMedia;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::factory()->hasMedia(3)->count(60)->create();
    }
}
