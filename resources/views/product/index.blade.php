<x-catalog::app-layout>
    <x-catalog::head-title>{{ __('Hàng Hoá Vật Tư') }}</x-catalog::head-title>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Hàng Hoá Vật Tư') }}
        </h2>
        <p>{{ __('Danh sách') }}</p>
    </x-slot>

    @livewire('catalog::product.products')
</x-catalog::app-layout>
