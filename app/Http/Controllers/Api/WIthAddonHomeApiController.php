<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class HomeApiController extends Controller
{
    public function index()
    {
        // Fetch all images_slider
        $images_slider = DB::table('images_slider')
            ->select('id', 'link', 'image')
            // ->latest()
            ->get()->map(function ($item) {
                $item->image = url($item->image);
                return $item;
            });
        // Step 1: Get top-level active categories (limit 6)
        $HsortPro = DB::table('categories')
            ->where('active_categorie', 1)
            ->orderBy('hp_sort_order', 'asc')
            ->select('id', 'name', 'slug')
            ->get();

        // Step 2: Attach up to 4 products per category
        $HsortPro = $HsortPro->map(function ($category) {
            $query = DB::table('products');
            $query->orderByDesc('category_feature_p')
                ->orderByDesc('id');

            $products = $query
                ->leftJoin('category_product', 'products.id', '=', 'category_product.product_id')
                ->where('products.active_product', 1)
                ->where(function ($query) use ($category) {
                    $query->where('products.category_id', $category->id) // one-to-many
                        ->orWhere('category_product.category_id', $category->id); // many-to-many
                })
                ->select('products.*') // important: avoid selecting category_product cols
                ->orderByDesc('products.id')
                ->distinct()
                ->get()
                ->map(function ($product) {
                    // Format product fields
                    $product->cover_image = url($product->cover_image);
                    $product->price = number_format($product->price, 0, '.', ',');
                    $product->cut_price = number_format($product->cut_price, 0, '.', ',');

                    // 🔹 Get addons for this product
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

                    return $product;
                });

            // Only include categories with products
            if ($products->isNotEmpty()) {
                $category->products = $products;
                return $category;
            }

            return null; // filter out empty categories
        })
            ->filter()
            ->values();

        return response()->json([
            'images_slider' => $images_slider,
            'HomeProducts' => $HsortPro,
        ]);
    }
}
