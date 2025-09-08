@extends('layouts.app')
@section('title')
    ALL Categorie
@endsection
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Categories</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <h6>
                        <a href={{ route('categories.hp_sort') }}>
                            <div class="text-tiny">Home Sort Categories</div>
                        </a>
                    </h6>
                    <h6>
                        <a href={{ route('categories.sort') }}>
                            <div class="text-tiny">Sort Categories</div>
                        </a>
                    </h6>
                    <li>
                        <a href={{ route('home') }}>
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Categories</div>
                    </li>

                </ul>
            </div>

            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <a class="tf-button style-1 w208" href={{ route('categories.create') }}><i class="icon-plus"></i>Add
                        new</a>
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
                            <select id="search_menu" class="form-control column-search">
                                <option value="">All Menu</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <!-- DataTable -->
                    <table class="custom-table-style my-5" id="categories_datable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                {{-- <th>Image</th> --}}
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Active Category</th>
                                <th>Feature Category</th>
                                <th>Show On Menu</th>
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
            var table = $('#categories_datable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('categories.getcategorys') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    // {
                    //     data: 'image',
                    //     name: 'image'
                    // },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'slug',
                        name: 'slug',
                        render: function(data, type, row) {
                            return `<a target="_blank" href="https://alhilalrestaurant.com/${data}">${data}</a>`;
                        }
                    },
                    {
                        data: 'active_categorie',
                        name: 'active_categorie',
                        searchable: true,
                        render: function(data, type, row) {
                            return data === 1 ?
                                '<span class="btn btn-success">Active</span>' :
                                '<span class="btn btn-danger">Inactive</span>';
                        }
                    },
                    {
                        data: 'feature_categorie',
                        name: 'feature_categorie',
                        searchable: true,
                        render: function(data, type, row) {
                            return data === 1 ?
                                '<span class="btn btn-success">Active</span>' :
                                '<span class="btn btn-danger">Inactive</span>';
                        }
                    },
                    {
                        data: 'show_on_menu',
                        name: 'show_on_menu',
                        searchable: true,
                        render: function(data, type, row) {
                            return data === 1 ?
                                '<span class="btn btn-success">Active</span>' :
                                '<span class="btn btn-danger">Inactive</span>';
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
                    case 'search_menu':
                        columnIndex = 6;
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
