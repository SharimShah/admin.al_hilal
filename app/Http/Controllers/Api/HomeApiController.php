<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class HomeApiController extends Controller
{
    public function index()
    {
        // Fetch all categories
        $categories = DB::table('categories')
            ->orderBy('hp_sort_order', 'asc')
            ->where('show_on_menu', 1)
            ->where('hidden', 0)
            ->where('feature_categorie', 1)
            ->where('active_categorie', 1)
            ->take(12)
            ->select('id', 'name', 'image', 'slug')
            ->latest()
            ->get()->map(function ($item) {
                $item->image = url($item->image);
                return $item;
            });
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
            ->where('hidden', 0)
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
                ->where('products.hidden', 0)
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
                    'products.discount_percentage',
                    'products.category_feature_p',
                    'products.delivery_price'
                )
                ->orderByDesc('products.id')
                ->distinct()
                ->get()
                ->map(function ($product) {
                    $product->cover_image = url($product->cover_image);
                    $product->price = number_format($product->price, 0, '.', ',');
                    $product->discount_percentage = number_format($product->discount_percentage, 0, '.', ',');
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
            'categories' => $categories,
            'HomeProducts' => $HsortPro,
        ]);
    }
    private function buildMenuTree($categories, $parentId = null)
    {
        $tree = [];

        foreach ($categories as $category) {
            if ($category->parent_id === $parentId) {
                $children = $this->buildMenuTree($categories, $category->id);

                $node = [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                ];

                if (!empty($children)) {
                    $node['children'] = $children;
                }

                $tree[] = $node;
            }
        }

        return $tree;
    }
}
