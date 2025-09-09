<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $customDetails = json_decode($order->custom_details, true);

        // Get product IDs
        $product_ids = collect($customDetails)->pluck('id')->toArray();

        // Get product slugs
        $product_links = DB::table('products')
            ->whereIn('id', $product_ids)
            ->pluck('slug', 'id')
            ->toArray();

        // Collect all addon IDs from customDetails
        $addon_ids = collect($customDetails)->flatMap(function ($item) {
            return collect($item['variations']['fields'] ?? [])
                ->flatten()
                ->filter(function ($v) {
                    return is_numeric($v); // keep only valid IDs
                });
        })->toArray();

        // Fetch addons (id => [sub_item_name, price])
        $addons = DB::table('product_addon_items')
            ->whereIn('id', $addon_ids)
            ->get()
            ->keyBy('id');

        return view('orders.show', compact('order', 'customDetails', 'product_links', 'addons'));
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
        DB::table('orders')->where('id', $id)->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully');
    }
}
