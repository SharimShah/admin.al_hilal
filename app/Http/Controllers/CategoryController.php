<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\File;
// use Intervention\Image\Encoders\WebpEncoder;
use Yajra\DataTables\Facades\DataTables;
// use Intervention\Image\Laravel\Facades\Image;

class CategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public $categories;
    public function __construct()
    {
        $this->middleware('auth');
        // Fetch all categories from the database
        $categories = DB::table('categories')->get();
        $this->categories = $categories;
    }
    public function create()
    {
        $categories = $this->buildCategoryTree($this->categories);
        return view('categories.form', compact('categories'));
    }
    public function index()
    {
        $categories = $this->buildCategoryTree($this->categories);
        return view('categories.index', compact('categories'));
    }
    public function edit($id)
    {
        $category = DB::table('categories')->where('id', $id)->first();
        $categories = $this->buildCategoryTree($this->categories);
        return view('categories.form', compact('category', 'categories'));
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
    public function getcategorys(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('categories')->latest()->select('*'); // Using Query Builder

            return DataTables::of($data)
                ->addIndexColumn()
                // ->addColumn('image', function ($data) {
                //     $imgPath = $data->image ? url($data->image) : url('images/appimg/noimg.jpeg');
                //     $img = '<img src="' . $imgPath . '" onerror="this.onerror=null;this.src=\'' . url('images/appimg/noimg.jpeg') . '\';" width="50" height="50" class="img-rounded" align="center" />';
                //     return $img;
                // })
                ->addColumn('action', function ($data) {
                    $edit = '<a href="' . route('categories.edit', [$data->id]) . '" class="edit btn btn-success">  
                <div class="item edit">
                    <i class="icon-edit-3"></i>
                </div>
             </a>';

                    $delete = '
               <form action="' . route('categories.destroy', [$data->id]) . '" method="POST" style="display:inline;">
                   ' . csrf_field() . '
                   ' . method_field('DELETE') . '
                   <button type="submit" class="btn btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</button>
               </form>';

                    return $edit . ' ' . $delete;
                })
                // ->rawColumns(['action', 'image'])
                ->rawColumns(['action'])
                ->make(true);
        }
    }
    // Store a new category
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // 'description_long' => 'nullable|string',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            // 'meta_title' => 'nullable|string|max:255',
            // 'meta_description' => 'nullable|string|max:255',
            // 'meta_keywords' => 'nullable|string|max:255',
            'show_on_menu' => 'nullable|boolean',
            'feature_categorie' => 'nullable|boolean',
            'active_categorie' => 'nullable|boolean',
            // 'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            // 'image_name' => 'nullable|string|max:255',
        ]);
        // Handle Image Upload
        // $imagePath = null;

        // if ($request->hasFile('image')) {
        //     $imagePath = $this->uploadImage($request->file('image'), 'category');
        // }
        // Generate a unique slug
        $slug = $request->input('slug') ?? Str::slug($request->input('name'));
        $originalSlug = $slug;
        $count = 1;
        while (DB::table('categories')->where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        // Insert category into the database
        DB::table('categories')->insert([
            'name' => $request->input('name'),
            'parent_id' => $request->input('parent_id'),
            // 'description_long' => $request->input('description_long'),
            // 'image' => $imagePath,
            // 'image_name' => $request->input('image_name'),
            'slug' => $slug,
            // 'meta_title' => $request->input('meta_title'),
            // 'meta_description' => $request->input('meta_description'),
            // 'meta_keywords' => $request->input('meta_keywords'),
            'show_on_menu' => $request->boolean('show_on_menu'),
            'feature_categorie' => $request->boolean('feature_categorie'),
            'active_categorie' => $request->boolean('active_categorie'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('categories.index')->with('success', 'Category added successfully!');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // 'description_long' => 'nullable|string',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $id,
            // 'meta_title' => 'nullable|string|max:255',
            // 'meta_description' => 'nullable|string|max:255',
            // 'meta_keywords' => 'nullable|string|max:255',
            'show_on_menu' => 'nullable|boolean',
            'feature_categorie' => 'nullable|boolean',
            'active_categorie' => 'nullable|boolean',
            // 'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            // 'image_name' => 'nullable|string|max:255',
        ]);

        // Fetch the existing category
        // $category = DB::table('categories')->where('id', $id)->first();
        // $imagePath = $category->image;
        // if ($request->hasFile('image')) {
        //     // Delete old image if exists
        //     if ($imagePath) {
        //         File::delete(public_path($imagePath));
        //     }

        //     // Upload new image
        //     $imagePath = $this->uploadImage($request->file('image'), 'category');
        // }
        // Slug handling
        $slug = $request->input('slug') ?? Str::slug($request->input('name'));
        $originalSlug = $slug;
        $count = 1;
        while (DB::table('categories')->where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        // Update the category
        DB::table('categories')
            ->where('id', $id)
            ->update([
                'name' => $request->input('name'),
                // 'description_long' => $request->input('description_long'),
                // 'image' => $imagePath,
                // 'image_name' => $request->input('image_name'),
                'slug' => $slug,
                // 'meta_title' => $request->input('meta_title'),
                // 'meta_description' => $request->input('meta_description'),
                // 'meta_keywords' => $request->input('meta_keywords'),
                'show_on_menu' => $request->boolean('show_on_menu'),
                'feature_categorie' => $request->boolean('feature_categorie'),
                'active_categorie' => $request->boolean('active_categorie'),
                'updated_at' => now(),
            ]);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
    }
    // Delete a category
    public function destroy($id)
    {
        // $category = DB::table('categories')->where('id', $id)->first();
        // if ($category && $category->image) {
        //     File::delete(public_path($category->image));
        // }
        DB::table('categories')->where('id', $id)->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully!');
    }
    public function sortForm()
    {
        $categories = DB::table('categories')
            ->whereNull('parent_id')
            ->where('active_categorie', 1)
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('categories.sort', compact('categories'));
    }
    public function updateOrder(Request $request)
    {
        foreach ($request->order as $item) {
            DB::table('categories')
                ->where('id', $item['id'])
                ->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['success' => true, 'message' => 'Order updated successfully']);
    }
    public function hp_sortForm()
    {
        $categories = DB::table('categories')
            ->where('active_categorie', 1)
            ->orderBy('hp_sort_order', 'asc')
            ->get();

        return view('categories.hp_sort', compact('categories'));
    }
    public function hp_updateOrder(Request $request)
    {
        foreach ($request->order as $item) {
            DB::table('categories')
                ->where('id', $item['id'])
                ->update(['hp_sort_order' => $item['hp_sort_order']]);
        }

        return response()->json(['success' => true, 'message' => 'Order updated successfully']);
    }

    // private function uploadImage($image, $folder)
    // {
    //     $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
    //     $extension = $image->getClientOriginalExtension(); // keep original extension
    //     $seoName = Str::slug($originalName); // SEO-friendly name
    //     $folderPath = public_path("images/$folder/");

    //     // Ensure the directory exists
    //     if (!File::exists($folderPath)) {
    //         File::makeDirectory($folderPath, 0755, true, true);
    //     }

    //     // Ensure unique filename
    //     $fileIndex = 1;
    //     $fileName = $seoName . '.' . $extension;
    //     while (File::exists($folderPath . $fileName)) {
    //         $fileIndex++;
    //         $fileName = $seoName . '-' . $fileIndex . '.' . $extension;
    //     }

    //     // Move the uploaded file as-is
    //     $image->move($folderPath, $fileName);

    //     // Return the relative path
    //     return "images/$folder/" . $fileName;
    // }
}
