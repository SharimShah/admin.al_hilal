@extends('layouts.app')

@section('title', 'All Orders')

@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center justify-between mb-4">
                <h3>Orders</h3>
                <ul class="breadcrumbs">
                    <li><a href="{{ route('home') }}">Dashboard</a></li>
                    <li>›</li>
                    <li>Orders</li>
                </ul>
            </div>

            <div class="wg-box">
                <table class="custom-table-style my-5" id="Orders_datatable">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Status</th>
                            <th>Name</th>
                            <th>Number</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(function() {
            $('#Orders_datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('orders.getorders') }}",
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'phone_number'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endsection
