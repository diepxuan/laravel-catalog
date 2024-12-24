<div class="flex w-100 my-2 items-center">
    <x-input-switch :checked="$product->status" />
    <div class="w-80 mx-2">
        <strong>{{ $product->name }}</strong>
        <a href="{{ route('catalog.show', $product->id) }}" class="text-decoration-none" target="_blank">
            <i class="bi bi-pencil-square"></i>
        </a>
    </div>
    <small class="w-10 px-2">{{ $product->quantity ?: 0 }}</small>
    <small class="w-15 px-2">{{ $price }}</small>
</div>
{{-- <div class="list-group-item list-group-item-action lh-sm">
            <div class="d-flex w-100 align-items-center justify-content-between">
                <form action="{{ route('catalog.update', $product->id) }}" method="POST">
                    @method('PUT') @csrf
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="status"
                            {{ $product->status ? 'checked' : '' }} onchange="this.form.submit()" />
                        <strong class="mb-1">
                            {{ $product->name }}
                            <a href="{{ route('catalog.show', $product->id) }}" class="text-decoration-none"
                                target="_blank">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        </strong>
                    </div>
                </form>
                <small>{{ $product->quantity ?: 0 }}</small>
            </div>
            <div class="mt-1 small row">
                <small class="col-md-6">
                    @php
                        if ($product->cat) {
                            $path = "{$product->cat->urlPath}/{$product->urlKey}";
                        } else {
                            $path = $product->urlKey;
                        }
                    @endphp
                    <a href="https://www.diepxuan.com/{{ $path }}.html" class="text-decoration-none"
                        target="_blank">
                        <i class="bi bi-link"></i>
                        {{ $product->sku }}
                    </a>
                </small>
                <small class="col-md-2 text-end">{{ $product->price }}</small>
                <small class="col-md-4">{{ implode(' | ', $product->catIds) }}</small>
            </div>
        </div> --}}
