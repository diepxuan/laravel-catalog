<tr class="border-t border-b">
    <td class="p-2 flex text-center align-middle">
        <x-input-switch :checked="$product->status" />
    </td>
    <td class="p-2">
        <strong>{{ $product->name }}</strong>
    </td>
    <td class="p-2">
        {{ $product->sku }}
    </td>
    <td class="p-2">
        {{ $product->quantity ?: 0 }}
    </td>
    <td class="p-2">
        @currency($product->price)
    </td>
    <td class="p-2">
        <a href="{{ route('catalog.show', $product->sku) }}" class="text-decoration-none" target="_blank">
            {{ _('detail') }}
        </a>
    </td>
</tr>
