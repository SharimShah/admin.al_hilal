<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;
class ProductController extends Controller
{

    public $categories;

    public function __construct()
    {
        $this->middleware('auth');
        // Fetch all categories from the database
        $categories = DB::table('categories')->get();
        $this->categories = $categories;
    }

    public function index()
    {
        $categories = DB::table('categories')->get();
        $products = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'categories.name as category_name')
            ->get();
        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = $this->buildCategoryTree($this->categories);
        $allcategories = $this->categories;
        return view('products.form', compact(
            'categories',
            'allcategories',
            // other stuff like product, categories etc.
        ));
    }
    public function getproducts(Request $request)
    {
        if ($request->ajax()) {
            // Get category ID from request
            $categoryId = $request->get('category_id');
            // Build base query with a join to the categories table
            $query = DB::table('products')
                ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                ->select('products.*', 'categories.name as category_name')
                ->latest();
            // Apply category filter if provided
            if (!empty($categoryId)) {
                $query->where('products.category_id', $categoryId);
            }
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('image', function ($data) {
                    $imgPath = $data->cover_image ? url($data->cover_image) : url('images/appimg/noimg.jpeg');
                    $img = '<img src="' . $imgPath . '" onerror="this.onerror=null;this.src=\'' . url('images/appimg/noimg.jpeg') . '\';" width="50" height="50" class="img-rounded" align="center" />';
                    return $img;
                })
                ->addColumn('category', function ($data) {
                    return $data->category_name ?? 'Uncategorized';
                })
                ->addColumn('action', function ($data) {
                    $edit = '<a href="' . route('products.edit', [$data->id]) . '" class="w-100 edit btn btn-success">
                            <div class="item edit">
                                <i class="icon-edit-3"></i>
                            </div>
                        </a>';

                    $delete = '
                    <form action="' . route('products.destroy', [$data->id]) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger w-100 mt-2" onclick="return confirm(\'Are you sure?\')">Delete</button>
                    </form>';

                    return $edit . ' ' . $delete;
                })
                ->rawColumns(['action', 'image'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'parent_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'meta_description' => 'required|string|max:255',
            'meta_keywords' => 'required|string|max:255',
            'price' => 'required|numeric',
            'discount_percentage' => 'nullable|numeric',
            'category_feature_p' => 'nullable|boolean',
            'hidden' => 'nullable|boolean',
            'feature_product' => 'nullable|boolean',
            'active_product' => 'nullable|boolean',
            'delivery_price' => 'nullable|boolean',
            'youtube_url' => 'nullable|url',
            'cover_image' => 'required|image|mimes:jpg,png,jpeg,webp|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
        ]);

        // Generate a unique slug if not provided
        $slug = $request->input('slug') ? $request->input('slug') : Str::slug($request->input('name'));
        $originalSlug = $slug;
        $count = 1;
        while (DB::table('products')->where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        // Handle Cover Image Upload with SEO-friendly URL
        $coverImagePath = $this->uploadImage($request->file('cover_image'), 'products');

        // Insert Product
        $productId = DB::table('products')->insertGetId([
            'category_id' => $request->parent_id,
            'name' => $request->name,
            'description' => $request->description,
            'long_description' => $request->long_description,
            'slug' => $slug,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'price' => $request->price,
            'discount_percentage' => $request->discount_percentage,
            'category_feature_p' => $request->has('category_feature_p'),
            'hidden' => $request->has('hidden'),
            // 'feature_product' => $request->has('feature_product'),
            'active_product' => $request->has('active_product'),
            'delivery_price' => $request->has('delivery_price'),
            'youtube_url' => $request->youtube_url,
            'cover_image' => $coverImagePath,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        foreach ($request->input('category_ids', []) as $catId) {
            DB::table('category_product')->insert([
                'product_id' => $productId,
                'category_id' => $catId,
            ]);
        }
        // Handle Gallery Images Upload
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $imagePath = $this->uploadImage($image, 'products');
                DB::table('images_gallery')->insert([
                    'product_id' => $productId,
                    'image_path' => $imagePath,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }
    // Show Edit Form
    public function edit($id)
    {
        $products = DB::table('products')->where('id', $id)->first();
        if ($products) { // Ensure product exists before accessing category_id
            $allcategories = $this->categories;
            $selectedCategory = DB::table('categories')
                ->where('id', $products->category_id)
                ->first();
        }
        //  else {
        //     $categories = null; // Handle missing product case
        //     $allcategories = null;
        // }
        $selectedCategoryIds = DB::table('category_product')
            ->where('product_id', $id)
            ->pluck('category_id')
            ->toArray();

        $galleryImages = DB::table('images_gallery')->where('product_id', $id)->get();
        // Get the currently selected form's fields
        $categories = $this->buildCategoryTree($this->categories);
        $allcategories = $this->categories;
        return view('products.form', compact(
            'products',
            'selectedCategoryIds',
            'selectedCategory',
            'categories',
            'allcategories',
            'galleryImages',
        ));
    }

    public function update(Request $request, $id)
    {
        // dd($request);
        $request->validate([
            'parent_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $id,
            'meta_description' => 'required|string|max:255',
            'meta_keywords' => 'required|string|max:255',
            'price' => 'required|numeric',
            'discount_percentage' => 'nullable|numeric',
            'category_feature_p' => 'nullable|boolean',
            'hidden' => 'nullable|boolean',
            'feature_product' => 'nullable|boolean',
            'active_product' => 'nullable|boolean',
            'delivery_price' => 'nullable|boolean',
            'youtube_url' => 'nullable|url',
            'cover_image' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
        ]);
        $product = DB::table('products')
            ->where('id', $id)
            ->first();
        if (!$product) {
            return redirect()->route('products.index')->with('error', 'Product not found!');
        }

        // Generate a unique slug
        $slug = $request->input('slug') ? $request->input('slug') : Str::slug($request->input('name'));
        $originalSlug = $slug;
        $count = 1;
        while (
            DB::table('products')
                ->where('slug', $slug)
                ->where('id', '<>', $id)
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $count++;
        }

        $updateData = [
            'category_id' => $request->parent_id,
            'name' => $request->name,
            'description' => $request->description,
            'long_description' => $request->long_description,
            'slug' => $slug,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'price' => $request->price,
            'discount_percentage' => $request->discount_percentage,
            'youtube_url' => $request->youtube_url,
            'feature_product' => $request->has('feature_product') ? 1 : 0,
            'category_feature_p' => $request->has('category_feature_p') ? 1 : 0,
            'hidden' => $request->has('hidden') ? 1 : 0,
            'active_product' => $request->has('active_product') ? 1 : 0,
            'delivery_price' => $request->has('delivery_price') ? 1 : 0,
            'updated_at' => now(),
        ];
        DB::table('category_product')->where('product_id', $product->id)->delete();

        foreach ($request->input('category_ids', []) as $catId) {
            DB::table('category_product')->insert([
                'product_id' => $product->id,
                'category_id' => $catId,
            ]);
        }
        // Handle Cover Image Update
        if ($request->hasFile('cover_image')) {
            if ($product->cover_image) {
                File::delete(public_path($product->cover_image));
            }
            $updateData['cover_image'] = $this->uploadImage($request->file('cover_image'), 'products');
        }

        DB::table('products')->where('id', $id)->update($updateData);

        // Handle Gallery Images Upload
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $imagePath = $this->uploadImage($image, 'products');
                DB::table('images_gallery')->insert([
                    'product_id' => $id,
                    'image_path' => $imagePath,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }
    /**
     * Function to build a hierarchical category tree.
     */
    private function buildCategoryTree($categories, $parentId = null, $depth = 0)
    {
        $tree = [];
        foreach ($categories as $category) {
            if ($category->parent_id == $parentId) {
                $category->children = $this->buildCategoryTree($categories, $category->id, $depth + 1);
                $tree[] = $category;
            }
        }
        return $tree;
    }

    // Delete product and images
    public function destroy($id)
    {
        // Delete gallery images
        $galleryImages = DB::table('images_gallery')->where('product_id', $id)->get();
        foreach ($galleryImages as $image) {
            File::delete(public_path($image->image_path));
        }
        DB::table('images_gallery')->where('product_id', $id)->delete();

        // Delete product cover image
        $product = DB::table('products')->where('id', $id)->first();
        File::delete(public_path($product->cover_image));

        // Delete product
        DB::table('products')->where('id', $id)->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
    private function uploadImage($image, $folder)
    {
        $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $image->getClientOriginalExtension(); // keep original extension
        $seoName = Str::slug($originalName); // SEO-friendly name
        $folderPath = public_path("images/$folder/");

        // Ensure the directory exists
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true, true);
        }

        // Ensure unique filename
        $fileIndex = 1;
        $fileName = $seoName . '.' . $extension;
        while (File::exists($folderPath . $fileName)) {
            $fileIndex++;
            $fileName = $seoName . '-' . $fileIndex . '.' . $extension;
        }

        // Move the uploaded file as-is
        $image->move($folderPath, $fileName);

        // Return the relative path
        return "images/$folder/" . $fileName;
    }
}
