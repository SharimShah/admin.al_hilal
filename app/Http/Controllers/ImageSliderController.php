<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Encoders\WebpEncoder;

class ImageSliderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $sliders = DB::table('images_slider')->get();
        return view('images_slider.index', compact('sliders'));
    }

    public function create()
    {
        return view('images_slider.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'link' => 'nullable',
            'image_name' => 'nullable|string|max:255',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $this->uploadImage($request->file('image'), 'images_slider');
        }

        DB::table('images_slider')->insert([
            'image' => $imagePath,
            'link' => $request->link,
            'image_name' => $request->image_name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('images_slider.index')->with('success', 'Image Uploaded Successfully');
    }


    public function edit($id)
    {
        $imageSlider = DB::table('images_slider')
            ->where('id', $id)
            ->first();
        return view('images_slider.form', compact('imageSlider'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'link' => 'nullable',
            'image_name' => 'nullable|string|max:255',
        ]);

        $slider = DB::table('images_slider')->where('id', $id)->first();

        if (!$slider) {
            return redirect()->route('images_slider.index')->with('error', 'Image not found.');
        }

        $imagePath = $slider->image;

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($imagePath) {
                File::delete(public_path($imagePath));
            }

            // Upload new image
            $imagePath = $this->uploadImage($request->file('image'), 'images_slider');
        }

        DB::table('images_slider')
            ->where('id', $id)
            ->update([
                'image' => $imagePath,
                'link' => $request->link,
                'image_name' => $request->image_name,
                'updated_at' => now(),
            ]);

        return redirect()->route('images_slider.index')->with('success', 'Image Updated Successfully');
    }

    public function destroy($id)
    {
        $slider = DB::table('images_slider')
            ->where('id', $id)
            ->first();

        if (!$slider) {
            return redirect()->route('images_slider.index')->with('error', 'Image not found.');
        }

        // Delete the image from storage
        File::delete(public_path($slider->image));

        DB::table('images_slider')->where('id', $id)->delete();

        return redirect()->route('images_slider.index')->with('success', 'Image Deleted Successfully');
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
