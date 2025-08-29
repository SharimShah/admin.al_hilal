<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('orders.index');
    }

    public function getorders(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('orders')->latest()->select('*');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($data) {
                    return $data->image ? '<img src="' . asset($data->image) . '" width="50" height="50" class="img-thumbnail"/>' : 'No Image';
                })
                ->addColumn('action', function ($data) {
                    return '
                        <a href="' . route('orders.show', $data->id) . '" class="btn btn-info">View</a>
                        <a href="' . route('orders.edit', $data->id) . '" class="btn btn-primary">Edit</a>
                        <form action="' . route('orders.destroy', $data->id) . '" method="POST" style="display:inline;">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger" onclick="return confirm(\'Are you sure?\')">
                                Delete
                            </button>
                        </form>';
                })
                ->rawColumns(['image', 'action'])
                ->make(true);
        }
    }

    public function show($id)
    {
        $order = DB::table('orders')->where('id', $id)->first();
        // dd($order);
        $customDetails = json_decode($order->custom_details, true); // Decode JSON to array
        // Get all product IDs from customDetails
        $product_ids = collect($customDetails)->pluck('id')->toArray();

        // Fetch slugs and product names from the products table
        $product_links = DB::table('products')
            ->whereIn('id', $product_ids)
            ->pluck('slug', 'id')  // returns [id => slug]
            ->toArray();

        return view('orders.show', compact('order', 'product_links'));
    }

    public function edit($id)
    {
        $order = DB::table('orders')->where('id', $id)->first();
        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, $id)
    {
        DB::table('orders')->where('id', $id)->update([
            'name' => $request->name,
            'status' => $request->status,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
        ]);

        return redirect()->route('orders.index')->with('success', 'Order updated successfully');
    }

    public function destroy($id)
    {
        $order = DB::table('orders')->where('id', $id)->first();

        if ($order && $order->image) {
            File::delete(public_path($order->image));
        }

        DB::table('orders')->where('id', $id)->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully');
    }
}
