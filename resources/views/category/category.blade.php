@foreach ($categories as $category)
    <ul>
        <li>
            <form action="{{ route('category.update', $category->id) }}" method="POST">
                @method('PUT') @csrf
                <input name="include_in_menu" type="checkbox" {{ $category->include_in_menu ? 'checked' : '' }}
                    onchange="this.form.submit()" />
            </form>
        </li>
        <li style="min-width: 25px;">{{ $category->magento_id }}</li>
        <li style="min-width: 50px;">{{ $category->sku }}</li>
        <li><b>{{ $category->name }}</b></li>
        <li>
            <i>
                <a href="https://www.diepxuan.com/{{ $category->urlKey }}.html" target="_blank">
                    {{ $category->urlKey }}
                </a>
            </i>
        </li>
    </ul>
    @if ($category->catChildrens->count())
        <ul class="childs">
            <li></li>
            <li>
                @include('catalog::category.category', ['categories' => $category->catChildrens])
            </li>
        </ul>
    @endif
@endforeach
