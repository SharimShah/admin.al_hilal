@extends('layouts.app')

@section('title', 'Edit Order')

@section('content')
    <div class="main-content-inner">
        <!-- main-content-wrap -->
        <div class="main-content-wrap">
            <div class="wg-box">
                <h2>Edit Order</h2>
                <form method="POST" action="{{ route('orders.update', $order->id) }}">
                    @csrf
                    @method('PUT')

                    <fieldset class="name">
                        <label>Name</label>
                        <input type="text" name="name" value="{{ $order->name }}" class="form-control">
                    </fieldset>
                    <div class="mb-3">
                        <label>Status</label>
                        {{-- <input type="text" name="status" value="{{ $order->status }}" class="form-control"> --}}
                        <select name="status">
                            <option value="New">New</option>
                            <option value="processing">processing</option>
                            <option value="confirmed">confirmed</option>
                            <option value="pending">pending</option>
                            <option value="shipped">shipped</option>
                            <option value="out_for_delivery">out_for_delivery</option>
                            <option value="delivered">delivered</option>
                            <option value="cancelled">cancelled</option>
                            <option value="failed">failed</option>
                            <option value="returned">returned</option>
                            <option value="refunded">refunded</option>
                            <option value="refunded">refunded</option>
                            @if (isset($order))
                                <option value="{{ $order->status }}" selected>{{ $order->status }}</option>
                            @endif
                        </select>
                        @error('status')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Phone</label>
                        <input type="text" name="phone_number" value="{{ $order->phone_number }}" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ $order->email }}" class="form-control">
                    </div>

                    <!-- Add other fields as needed -->

                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
