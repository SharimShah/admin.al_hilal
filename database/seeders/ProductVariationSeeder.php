<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductVariationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Use existing product
        $productId = DB::table('products')->first()->id;

        // Insert types
        $sizeId = DB::table('variation_types')->insertGetId(['name' => 'Size']);
        $colorId = DB::table('variation_types')->insertGetId(['name' => 'Color']);

        // Insert values
        $v1 = DB::table('variation_values')->insertGetId(['variation_type_id' => $sizeId, 'value' => '19x6']);
        $v2 = DB::table('variation_values')->insertGetId(['variation_type_id' => $sizeId, 'value' => '20x8']);
        $v3 = DB::table('variation_values')->insertGetId(['variation_type_id' => $colorId, 'value' => 'Red']);
        $v4 = DB::table('variation_values')->insertGetId(['variation_type_id' => $colorId, 'value' => 'Blue']);

        // Link variations with product
        DB::table('product_variations')->insert([
            ['product_id' => $productId, 'variation_value_id' => $v1, 'price' => 200],
            ['product_id' => $productId, 'variation_value_id' => $v2, 'price' => 250],
            ['product_id' => $productId, 'variation_value_id' => $v3, 'price' => 200],
            ['product_id' => $productId, 'variation_value_id' => $v4, 'price' => 250],
        ]);
    }
}
