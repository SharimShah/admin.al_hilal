@extends('layouts.app')
{{-- @dd($c_Images->order_id) --}}
@section('title', 'View Order')

@section('content')
    <div class="container">
        <h2 class="mb-4">Order Details</h2>

        {{-- Basic Order Fields --}}
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <tbody>
                    <tr>
                        <th>From Name</th>
                        <td>{{ $order->from_name }}</td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td>{{ $order->name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $order->email }}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ $order->phone_number }}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>{{ $order->address }}</td>
                    </tr>
                    <tr>
                        <th>Postal Code</th>
                        <td>{{ $order->postal_code }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ $order->status }}</td>
                    </tr>
                    <tr>
                        <th>Total Price</th>
                        <td><strong>{{ $order->total_price }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        Custom Product Details
        @php
            $customDetails = json_decode($order->custom_details, true);
        @endphp

        <h5 class="mt-5">Product Customization</h5>
        {{-- @if (!empty($customDetails))
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm mt-3 w-full" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>Product ID</th>
                            <th>Quantity</th>
                            <th style="width:30%">Product Link</th>
                            <th>Instructions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customDetails as $item)
                            <tr>
                                <td>{{ $item['id'] }}</td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>
                                    @if (isset($product_links[$item['id']]))
                                        <a href="{{ 'https://alhilalrestaurant.com/product/' . $product_links[$item['id']] }}"
                                            target="_blank">
                                            {{ 'https://alhilalrestaurant.com/product/' . $product_links[$item['id']] }}
                                        </a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @if (!empty($item['variations']))
                                        @foreach ($item['variations'] as $key => $value)
                                            @if (is_array($value))
                                                @foreach ($value as $subKey => $subValue)
                                                    <strong>{{ ucfirst(str_replace('_', ' ', $subKey)) }}:</strong>
                                                    {{ $subValue }}<br>
                                                @endforeach
                                            @else
                                                @if ($key !== 'custom_images')
                                                    <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                                    {{ $value }}<br>
                                                @endif
                                            @endif
                                        @endforeach
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        @else
            <p class="text-muted">No customization details available.</p>
        @endif --}}
        {{-- Back Button --}}
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary mt-4">← Back to Orders</a>
    </div>
@endsection
