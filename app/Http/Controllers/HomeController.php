<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $totalProducts = DB::table('products')->count();
        $totalCategories = DB::table('categories')->count();
        $totalSlider = DB::table('images_slider')->count();
        return view('dashboard', compact('totalProducts', 'totalCategories', 'totalSlider'));
    }
}
