<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function show($slug)
    {
        // Fetch product by slug
        $product = DB::table('products')
            ->whereRaw('BINARY `slug` = ?', [$slug])
            ->where('active_product', 1)
            ->first();

        // Check if product exists
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        // Format product fields
        $product->cover_image = url($product->cover_image);
        $product->price = number_format($product->price, 0, '.', ',');
        $product->cut_price = number_format($product->cut_price, 0, '.', ',');

        // 🔹 Fetch addons
        $addons = DB::table('product_addons')
            ->where('product_id', $product->id)
            ->orderBy('sequence')
            ->get()
            ->map(function ($addon) {
                $items = DB::table('product_addon_items')
                    ->where('product_addon_id', $addon->id)
                    ->get()
                    ->map(function ($item) {
                        return [
                            'sub_item_id' => (string) $item->id,
                            'sub_item_name' => $item->sub_item_name,
                            'price' => (float) $item->price,
                            'checked' => (bool) $item->checked,
                        ];
                    });

                return [
                    'subcat_id' => (string) $addon->id,
                    'sequence' => $addon->sequence,
                    'subcat_name' => $addon->subcat_name,
                    'multi_option' => $addon->multi_option,
                    'require_addons' => (bool) $addon->require_addons,
                    'sub_item' => $items,
                ];
            });

        $product->addons = $addons;

        return response()->json([
            'success' => true,
            'product' => $product,
        ]);
    }
}
