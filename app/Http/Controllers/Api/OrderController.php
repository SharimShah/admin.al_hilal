<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    public function store(Request $request)
    {
        // Step 2: Extract order data
        $orderData = $request->only([
            'name',
            'email',
            'phone_number',
            'address',
            'from_name',
            'total_price',
            'custom_details',
        ]);
        // Step 5: Status and timestamps
        $orderData['status'] = 'New';
        $orderData['created_at'] = now();
        $orderData['updated_at'] = now();
        // Step 6: Save to database
        try {
            $orderId = DB::table('orders')->insertGetId($orderData);
            $orderData['id'] = $orderId;
            // Send response first
            $response = response()->json([
                'order' => true,
                'message' => 'Order saved successfully',
                'order_id' => $orderId
            ], 201);

            // Then queue the email
            // Mail::to($orderData['email'])->queue(new OrderMail($orderData));

            return $response;
        } catch (\Exception $e) {
            return response()->json([
                'order' => false,
                'error' => 'Failed to save order',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
