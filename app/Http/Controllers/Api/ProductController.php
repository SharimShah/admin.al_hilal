<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

        // Convert cover image to full URL
        $product->cover_image = url($product->cover_image);
        $product->price = number_format($product->price, 0, '.', ',');
        $product->discount_percentage = number_format($product->discount_percentage, 0, '.', ',');
        // Fetch product gallery images
        $galleryImages = DB::table('images_gallery')
            ->where('product_id', $product->id)
            ->get()
            ->map(function ($image) {
                $image->image_path = url($image->image_path); // Convert to full URL
                return $image;
            })
            ->pluck('image_path')
            ->toArray();

        // Insert cover image at index 0
        array_unshift($galleryImages, $product->cover_image);
        $product->gallery_images = $galleryImages;

        return response()->json([
            'success' => true,
            'product' => $product,
        ]);
    }
    private function getParentCategories($categories, $category, &$parents = [])
    {
        foreach ($categories as $cat) {
            if ($cat->id == $category->parent_id) {
                $parents[] = $cat;
                $this->getParentCategories($categories, $cat, $parents);
            }
        }
        return $parents;
    }
}
