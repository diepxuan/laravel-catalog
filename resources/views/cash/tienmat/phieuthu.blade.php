<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Phiếu thu') }}
        </h2>
    </x-slot>

    {{-- Diepxuan\Catalog\Http\Livewire\Cash\Tienmat\Phieuthu --}}
    @livewire('catalog::cash.tienmat.phieuthu.dashboard')
</x-app-layout>
