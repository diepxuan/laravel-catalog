<div class="flex w-100 my-2 items-center">
    <x-input-switch :checked="$product->status" />
    <div class="w-80 mx-2">
        <strong>{{ $product->name }}</strong>
        <a href="{{ route('catalog.show', $product->id) }}" class="text-decoration-none" target="_blank">
            <i class="bi bi-pencil-square"></i>
        </a>
    </div>
    <small class="w-10 px-2">{{ $product->quantity ?: 0 }}</small>
    <small class="w-15 px-2">@currency($product->price)</small>
</div>
