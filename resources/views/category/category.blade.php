@foreach ($categories as $category)
    <div class="list-group list-group-flush border-top border-start">
        <div class="list-group-item list-group-item-action lh-sm" aria-current="true">
            <div class="d-flex w-100 align-items-center justify-content-between">
                <form action="{{ route('category.update', $category->id) }}" method="POST">
                    @method('PUT') @csrf
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="include_in_menu"
                            {{ $category->include_in_menu ? 'checked' : '' }} onchange="this.form.submit()" />
                        <strong class="mb-1">{{ $category->name }}</strong>
                    </div>
                </form>
                @if ($category->urlPath)
                    <small>
                        <a href="https://www.diepxuan.com/{{ $category->urlPath }}.html" class="text-decoration-none"
                            target="_blank">
                            {{ $category->urlKey }}
                            <i class="bi bi-link"></i>
                        </a>
                    </small>
                @endif
            </div>
            <div class="col-10 mb-1 small">
                <small>{{ $category->sku }}</small>
                <small>{{ $category->magento->default }}</small>
                <small>{{ $category->magento->everon }}</small>
            </div>
            <div class="col-2 mb-1 small">
                <small>{{ implode(',', $category->ids) }}</small>
            </div>
        </div>
    </div>
    @if ($category->catChildrens->count())
        <div class="ps-5 border-start">
            @include('catalog::category.category', ['categories' => $category->catChildrens])
        </div>
    @endif
@endforeach
