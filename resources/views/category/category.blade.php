@foreach ($categories as $category)
    <div class="list-group list-group-flush border-top border-start">
        <div class="list-group-item list-group-item-action lh-sm" aria-current="true">
            <div class="d-flex w-100 align-items-center justify-content-between">
                <form action="{{ route('category.update', $category->id) }}" method="POST">
                    @method('PUT') @csrf
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="include_in_menu"
                            {{ $category->include_in_menu ? 'checked' : '' }} onchange="this.form.submit()" />
                        @if ($category->urlPath)
                            <a href="https://www.diepxuan.com/{{ $category->urlPath }}.html"
                                class="text-decoration-none" target="_blank">
                                <small class="mb-1"> {{ $category->sku }}</small>
                                <strong class="mb-1">{{ $category->name }}</strong>
                            </a>
                        @else
                            <small class="mb-1"> {{ $category->sku }}</small>
                            <strong class="mb-1">{{ $category->name }}</strong>
                        @endif
                    </div>
                </form>
            </div>
            <div class="col-10 mb-1 small">
                {{-- <small>default {{ $category->magento->default }}</small> --}}
                {{-- <small>everon {{ $category->magento->everon }}</small> --}}
            </div>
            {{-- <div class="col-2 mb-1 small">
                <small>{{ implode(',', $category->ids) }}</small>
            </div> --}}
        </div>
    </div>
    @if ($category->catChildrens->count())
        <div class="ps-5 border-start">
            @include('catalog::category.category', ['categories' => $category->catChildrens])
        </div>
    @endif
@endforeach
