@extends('layouts.app')

@section('title')
    Sort Categories
@endsection

@section('content')
    <div class="container mt-4">
        <h2 class="mb-3">Sort Categories</h2>

        <ul id="sortable" class="list-group">
            @foreach ($categories as $category)
                <li class="list-group-item" data-id="{{ $category->id }}" style="cursor: move;">
                    {{ $category->name }}
                </li>
            @endforeach
        </ul>

        <button class="btn btn-success mt-3" id="saveOrder">Save Order</button>
    </div>

    {{-- jQuery + jQuery UI --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    {{-- Optional: Minimal styling to enable drag handles --}}
    <style>
        #sortable li {
            padding: 10px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            margin-bottom: 5px;
        }
    </style>

    <script>
        $(function() {
            $('#sortable').sortable();
            $('#sortable').disableSelection();

            $('#saveOrder').on('click', function() {
                const order = [];
                $('#sortable li').each(function(index) {
                    order.push({
                        id: $(this).data('id'),
                        hp_sort_order: index + 1
                    });
                });

                $.ajax({
                    url: '{{ route('categories.hp_updateOrder') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        order: order
                    },
                    success: function(response) {
                        alert(response.message || 'Order saved!');
                    },
                    error: function() {
                        alert('Something went wrong.');
                    }
                });
            });
        });
    </script>
@endsection
