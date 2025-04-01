<div>
    <table class="table-auto w-full my-2">
        <thead>
            <tr>
                <th class="p2">Status</th>
                <th class="p2">Name</th>
                <th class="p2">SKU</th>
                <th class="p2">Quantity</th>
                <th class="p2">Price</th>
                <th class="p2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                @livewire('catalog::product.product', ['product' => $product], key($product->id))
            @endforeach

        </tbody>
    </table>

    {{-- @if ($hasMore)
        <div class="mt-4 text-center" id="loading-indicator">
            <button wire:click="loadProducts" class="px-4 py-2 bg-blue-300 text-white rounded hover:bg-blue-600">
                Load More
            </button>
        </div>
    @else
        <p class="mt-4 text-center text-gray-500">No more products to load.</p>
    @endif --}}
</div>
