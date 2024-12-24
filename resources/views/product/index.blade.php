<x-catalog::app-layout>
    <x-catalog::head-title>{{ __('Nhóm Hàng Hoá Vật Tư') }}</x-catalog::head-title>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nhóm Hàng Hoá Vật Tư') }}
        </h2>
        <p>{{ __('Danh sách') }}</p>
    </x-slot>

    @foreach ($products as $product)
        @livewire('catalog::product.products', ['product' => $product], key($product->id))
    @endforeach
</x-catalog::app-layout>
