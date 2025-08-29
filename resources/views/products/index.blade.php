@extends('layouts.app')
@section('title')
    All Products
@endsection
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>All Products</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href={{ route('home') }}>
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">All Products</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <a class="tf-button style-1 w208" href={{ route('products.create') }}><i class="icon-plus"></i>Add
                        new</a>
                </div>
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <select id="categoryFilter" class="form-control">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="wg-table table-all-user">
                    <!-- Search Inputs Above Table -->
                    <div class="row mb-3 custom-search-input">
                        <div class="col-md-2">
                            <input type="text" id="search_id" class="form-control column-search" placeholder="Search ID">
                        </div>
                        <div class="col-md-2">
                            <input type="text" id="search_name" class="form-control column-search"
                                placeholder="Search Name">
                        </div>
                        <div class="col-md-2">
                            <input type="text" id="search_slug" class="form-control column-search"
                                placeholder="Search Slug">
                        </div>
                        <div class="col-md-2">
                            <select id="search_active" class="form-control column-search">
                                <option value="">All Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="search_featured" class="form-control column-search">
                                <option value="">All Feature</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="search_hidden" class="form-control column-search">
                                <option value="">Out Of Stock</option>
                                <option value="1">Active</option>
                                <option value="0">No Active</option>
                            </select>
                        </div>
                    </div>

                    <!-- DataTable -->
                    <table class="custom-table-style my-5" id="products_datable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Active Products</th>
                                <th>Feature Products</th>
                                <th>Free Delivery</th>
                                <th>Out Of Stock</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">

                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#products_datable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('products.getproducts') }}",
                    data: function(d) {
                        d.category_id = $('#categoryFilter').val(); // Pass selected category ID
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'image',
                        name: 'image'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'slug',
                        name: 'slug',
                        render: function(data, type, row) {
                            return `<a target="_blank" href="https://alhilalrestaurant.com/product/${data}">${data}</a>`;
                        }
                    },
                    {
                        data: 'active_product',
                        name: 'active_product',
                        searchable: true,
                        render: function(data, type, row) {
                            return data === 1 ?
                                '<span class="btn btn-success">Active</span>' :
                                '<span class="btn btn-danger">Inactive</span>';
                        }
                    },
                    {
                        data: 'feature_product',
                        name: 'feature_product',
                        searchable: true,
                        render: function(data, type, row) {
                            return data === 1 ?
                                '<span class="btn btn-success">Active</span>' :
                                '<span class="btn btn-danger">Inactive</span>';
                        }
                    },
                    {
                        data: 'delivery_price',
                        name: 'delivery_price',
                        searchable: true,
                        render: function(data, type, row) {
                            return data === 1 ?
                                '<span class="btn btn-success">Active</span>' :
                                '<span class="btn btn-danger">Inactive</span>';
                        }
                    },
                    {
                        data: 'hidden',
                        name: 'hidden',
                        searchable: true,
                        render: function(data, type, row) {
                            return data === 1 ?
                                '<span class="btn btn-success">Yes</span>' :
                                '<span class="btn btn-danger">No</span>';
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
            // Trigger table reload on dropdown change
            $('#categoryFilter').on('change', function() {
                table.draw();
            });

            // Apply column search when typing or selecting from dropdown
            $('.column-search').on('keyup change', function() {
                let columnId = $(this).attr('id');
                let columnIndex;

                // Match input field with DataTable column index
                switch (columnId) {
                    case 'search_id':
                        columnIndex = 0;
                        break;
                    case 'search_name':
                        columnIndex = 2;
                        break;
                    case 'search_slug':
                        columnIndex = 3;
                        break;
                    case 'search_active':
                        columnIndex = 4;
                        break;
                    case 'search_featured':
                        columnIndex = 5;
                        break;
                    case 'search_hidden':
                        columnIndex = 7;
                        break;
                    default:
                        return;
                }

                // Apply search value to the column
                table.column(columnIndex).search(this.value).draw();
            });
        });
    </script>
@endsection
