@foreach ($categories as $category)
    <div class="list-group list-group-flush border-top border-start">
        <div class="list-group-item list-group-item-action lh-sm" aria-current="true">
            <div class="d-flex w-100 align-items-center justify-content-between">
                <form action="{{ route('diepxuan.category.update', $category->id) }}" method="POST">
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
            {{-- @isset($category->magento) --}}
            <div class="col-10 mb-1 small" id="{{ $category->id }}">
                <script>
                    fetch(`{{ route('api.category.show', $category->id) }}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            const posts = data; // Dữ liệu trả về từ API
                            document.getElementById(`{{ $category->id }}`).innerHTML = posts;
                        })
                        .catch(error => {
                            console.error(`Error fetching posts:`, error);
                        });
                </script>
                {{-- <small>{{ $category->magento->name }}</small> --}}
                {{-- <small>everon {{ $category->magento->everon }}</small> --}}
            </div>
            {{-- @endisset --}}
            {{-- <div class="col-2 mb-1 small">
                <small>{{ implode(',', $category->ids) }}</small>
            </div> --}}
        </div>
    </div>
    @if ($category->catChildrens->count())
        <div class="ps-4 border-start">
            @include('catalog::category.category', ['categories' => $category->catChildrens])
        </div>
    @endif
@endforeach
