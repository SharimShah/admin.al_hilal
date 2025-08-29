<option value="{{ $category->id }}">
    {{ $parentName ?? '' }}{{ $category->name }}
</option>

@if (!empty($category->children))
    @foreach ($category->children as $child)
        @include('categories.partials.category-option', [
            'category' => $child,
            'parentName' => ($parentName ?? '') . $category->name . ' > ',
        ])
    @endforeach
@endif
