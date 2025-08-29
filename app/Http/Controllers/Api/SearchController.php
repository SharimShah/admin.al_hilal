<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        $limit = (int) $request->input('limit', 30);
        $skip = (int) $request->input('skip', 0);

        if (!$query || trim($query) === '') {
            return response()->json([
                'products' => [],
                'total' => 0,
                'skip' => $skip,
                'limit' => $limit,
                'suggestions' => [
                    'products' => [],
                    'categories' => []
                ]
            ]);
        }

        // Get matching category IDs
        $categoryIds = DB::table('categories')
            ->where('active_categorie', 1)
            ->where('name', 'like', '%' . $query . '%')
            ->pluck('id')
            ->toArray();

        // Count total matching products
        $total = DB::table('products')
            ->where('active_product', 1)
            ->where(function ($q) use ($query, $categoryIds) {
                $q->where('name', 'like', '%' . $query . '%');
                if (!empty($categoryIds)) {
                    $q->orWhereIn('category_id', $categoryIds);
                }
            })
            ->count();

        // Fetch paginated products
        $products = DB::table('products')
            ->where('active_product', 1)
            ->where(function ($q) use ($query, $categoryIds) {
                $q->where('name', 'like', '%' . $query . '%');
                if (!empty($categoryIds)) {
                    $q->orWhereIn('category_id', $categoryIds);
                }
            })
            ->select('id', 'name', 'price', 'cover_image', 'slug', 'discount_percentage')
            ->skip($skip)
            ->take($limit)
            ->get()
            ->map(function ($product) {
                $product->cover_image = url($product->cover_image ?? 'default.jpg');
                $product->price = number_format($product->price, 0, '.', ',');
                $product->discount_percentage = number_format($product->discount_percentage ?? 0, 0, '.', ',');
                return $product;
            });

        // Product suggestions (based only on name match)
        $productSuggestions = DB::table('products')
            ->where('active_product', 1)
            ->take(20)
            ->where('name', 'like', '%' . $query . '%')
            ->select('id', 'name', 'price', 'cover_image', 'slug', 'discount_percentage')
            ->get()
            ->map(function ($product) {
                $product->cover_image = url($product->cover_image ?? 'default.jpg');
                $product->price = number_format($product->price, 0, '.', ',');
                $product->discount_percentage = number_format($product->discount_percentage ?? 0, 0, '.', ',');
                return $product;
            });

        // Category name suggestions
        $categorySuggestions = DB::table('categories')
            ->select('id', 'name', 'image', 'slug')
            ->where('active_categorie', 1)
            ->where('name', 'like', '%' . $query . '%')
            ->get()->map(function ($category) {
                $category->image = url($category->image);
                return $category;
            });

        return response()->json([
            'products' => $products,
            'total' => $total,
            'skip' => $skip,
            'limit' => $limit,
            'suggestions' => [
                'products' => $productSuggestions,
                'categories' => $categorySuggestions
            ]
        ]);
    }
}
