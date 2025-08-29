<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageSliderController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);
Route::middleware(['auth'])->group(
    function () {
        // Categories route
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('get-categories', [CategoryController::class, 'getcategorys'])->name('categories.getcategorys');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories/store', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/edit/{id}', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::post('/categories/update/{id}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/destroy/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        Route::get('get-products', [ProductController::class, 'getproducts'])->name('products.getproducts');
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/edit/{id}', [ProductController::class, 'edit'])->name('products.edit');
        Route::post('/products/update/{id}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/destroy/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::get('/images-slider', [ImageSliderController::class, 'index'])->name('images_slider.index');
        Route::get('/images-slider/create', [ImageSliderController::class, 'create'])->name('images_slider.create');
        Route::post('/images-slider/store', [ImageSliderController::class, 'store'])->name('images_slider.store');
        Route::get('/images-slider/edit/{id}', [ImageSliderController::class, 'edit'])->name('images_slider.edit');
        Route::put('/images-slider/update/{id}', [ImageSliderController::class, 'update'])->name('images_slider.update');
        Route::delete('/images-slider/destroy/{id}', [ImageSliderController::class, 'destroy'])->name('images_slider.destroy');
        Route::get('/orders/getorders', [OrderController::class, 'getorders'])->name('orders.getorders');
        Route::resource('orders', OrderController::class);
        Route::get('/categories/sort', [CategoryController::class, 'sortForm'])->name('categories.sort');
        Route::post('/categories/update-order', [CategoryController::class, 'updateOrder'])->name('categories.updateOrder');
        Route::get('/categories/hp-sort', [CategoryController::class, 'hp_sortForm'])->name('categories.hp_sort');
        Route::post('/categories/hp-update-order', [CategoryController::class, 'hp_updateOrder'])->name('categories.hp_updateOrder');
    }
);
