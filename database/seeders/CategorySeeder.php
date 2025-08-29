<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Http\Request;

class CategorySeeder extends Seeder

{
    public function run()
    {
        // Creating root categories
        $electronics = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'generated_slug' => 'electronics-2025',
            'img' => 'electronics.jpg',
            'page_description' => 'All electronics products',
            'meta_title' => 'Electronics - Shop Now',
            'meta_description' => 'Discover the latest electronics',
            'meta_keywords' => 'electronics, gadgets, tech',
        ]);

        $clothing = Category::create([
            'name' => 'Clothing',
            'slug' => 'clothing',
            'generated_slug' => 'clothing-2025',
            'img' => 'clothing.jpg',
            'page_description' => 'Fashionable clothing for all seasons',
            'meta_title' => 'Clothing - Fashion for You',
            'meta_description' => 'Explore trendy clothing collections.',
            'meta_keywords' => 'fashion, clothing, apparel',
        ]);

        // Creating subcategories under Electronics
        $mobilePhones = Category::create([
            'name' => 'Mobile Phones',
            'slug' => 'mobile-phones',
            'generated_slug' => 'mobile-phones-2025',
            'img' => 'mobile-phones.jpg',
            'page_description' => 'Latest smartphones and mobile gadgets',
            'meta_title' => 'Mobile Phones - Best Deals',
            'meta_description' => 'Explore top mobile phones and accessories.',
            'meta_keywords' => 'mobile phones, smartphones, gadgets',
            'parent_id' => $electronics->id,  // Subcategory of Electronics
        ]);

        $laptops = Category::create([
            'name' => 'Laptops',
            'slug' => 'laptops',
            'generated_slug' => 'laptops-2025',
            'img' => 'laptops.jpg',
            'page_description' => 'Explore the latest laptops',
            'meta_title' => 'Laptops - Buy the Latest Laptops',
            'meta_description' => 'Explore laptops for every need.',
            'meta_keywords' => 'laptops, computers, tech',
            'parent_id' => $electronics->id,  // Subcategory of Electronics
        ]);

        // Creating subcategories under Clothing
        $mensClothing = Category::create([
            'name' => 'Men\'s Clothing',
            'slug' => 'mens-clothing',
            'generated_slug' => 'mens-clothing-2025',
            'img' => 'mens-clothing.jpg',
            'page_description' => 'Shop for men\'s clothing',
            'meta_title' => 'Men\'s Clothing - Fashion for Men',
            'meta_description' => 'Explore the best men\'s clothing collections.',
            'meta_keywords' => 'mens clothing, fashion, apparel',
            'parent_id' => $clothing->id,  // Subcategory of Clothing
        ]);

        $womensClothing = Category::create([
            'name' => 'Women\'s Clothing',
            'slug' => 'womens-clothing',
            'generated_slug' => 'womens-clothing-2025',
            'img' => 'womens-clothing.jpg',
            'page_description' => 'Fashionable clothing for women',
            'meta_title' => 'Women\'s Clothing - Fashion for Women',
            'meta_description' => 'Discover trendy clothing collections for women.',
            'meta_keywords' => 'womens clothing, fashion, apparel',
            'parent_id' => $clothing->id,  // Subcategory of Clothing
        ]);
    }
}
