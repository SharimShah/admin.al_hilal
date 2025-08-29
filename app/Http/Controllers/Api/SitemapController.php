<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class SitemapController extends Controller
{
    public function index()
    {
        $domain = 'https://alhilalrestaurant.com/';

        // Products with updated_at
        $productUrls = DB::table('products')
            ->where('active_product', 1)
            ->get(['slug', 'updated_at'])
            ->map(function ($item) use ($domain) {
                return [
                    'loc' => $domain . 'product/' . ltrim($item->slug, '/'),
                    'lastmod' => Carbon::parse($item->updated_at)->toDateString(), // Format: YYYY-MM-DD
                ];
            });

        // Categories with updated_at
        $categoryUrls = DB::table('categories')
            ->where('active_categorie', 1)
            ->get(['slug', 'updated_at'])
            ->map(function ($item) use ($domain) {
                return [
                    'loc' => $domain . ltrim($item->slug, '/'),
                    'lastmod' => Carbon::parse($item->updated_at)->toDateString(),
                ];
            });

        // Combine all URLs
        $allUrls = $productUrls
            ->merge($categoryUrls)
            ->values();

        return response()->json($allUrls);
    }
}
