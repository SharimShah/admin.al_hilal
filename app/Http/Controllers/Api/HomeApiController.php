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
            ->select('id', 'name', 'slug', 'image')
            ->get()
            ->map(function ($HsortPro) {
                $HsortPro->image = !empty($HsortPro->image) ? url($HsortPro->image) : null;
                return $HsortPro;
            });


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
                ->select(
                    'products.id',
                    'products.name',
                    'products.slug',
                    'products.price',
                    'products.description',
                    'products.cover_image',
                    'products.cut_price',
                    'products.category_feature_p',
                    'products.free_delivery',
                    'products.stock'
                )
                ->orderByDesc('products.id')
                ->distinct()
                ->get()
                ->map(function ($product) {
                    $product->cover_image = url($product->cover_image);
                    $product->price = number_format($product->price, 0, '.', ',');
                    $product->cut_price = number_format($product->cut_price, 0, '.', ',');
                    return $product;
                });

            // Only include categories with products
            if ($products->isNotEmpty()) {
                $category->products = $products;
                return $category;
            }

            // Return null for categories with no products
            return null;
        })
            ->filter() // Removes nulls
            ->values(); // Reset array keys

        return response()->json([
            'images_slider' => $images_slider,
            'HomeProducts' => $HsortPro,
        ]);
    }
}
