<?php
// api token "1|iWFr6ACtG9Qy9aLuP2hi3s4ouzWt651A5jri1nHsf988bd0d"
use App\Http\Controllers\Api\HomeApiController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\SitemapController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/home', [HomeApiController::class, 'index']);
    Route::get('/product/{slug}', [ProductController::class, 'show']);
    Route::get('/search', [SearchController::class, 'search']);
    Route::get('/sitemap', [SitemapController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
});
