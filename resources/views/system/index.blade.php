<x-catalog::app-layout>
    <x-catalog::head-title>{{ __('Hệ Thống') }}</x-catalog::head-title>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Hệ Thống') }}
        </h2>
        <p>{{ __('Khoá Số Liệu') }}</p>
    </x-slot>

    @livewire('catalog::system.dashboard')
</x-catalog::app-layout>
