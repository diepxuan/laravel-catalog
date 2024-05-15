@foreach ($categories as $category)
    <ul>
        <li style="min-width: 25px;">{{ $category->id }}</li>
        <li style="min-width: 50px;">{{ $category->sku }}</li>
        <li><b>{{ $category->name }}</b></li>
        <li><i>{{ $category->urlKey }}</i></li>
    </ul>
    @if ($category->children->count())
        <ul class="childs">
            <li></li>
            <li>
                @include('catalog::category.category', ['categories' => $category->children])
            </li>
        </ul>
    @endif
@endforeach
