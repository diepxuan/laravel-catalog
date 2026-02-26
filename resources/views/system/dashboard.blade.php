<x-catalog::head-title>{{ __('Hệ Thống') }}</x-catalog::head-title>
<x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ __('Hệ Thống') }}
    </h2>
    <p>{{ __('Khoá Số Liệu') }}</p>
</x-slot>

<div wire:init="loadData" class="mx-4 my-2">

    <h1 class="mt-1 text-2xl font-medium text-gray-900">
        {{ $system->ten_cty }}
    </h1>

    <table class="table-sm table-hover mt-6 table align-middle leading-relaxed text-gray-500">
        <tr>
            <td>{{ 'Khoá số liệu:' }}</td>
            <td>
                <input type="date" placeholder="dd/mm/yyyy" wire:model="ngayKhoaSo" wire:change="updateKhoaSo()" />
            </td>
            <td>
                <p class="m-auto cursor-default rounded-t-lg px-3 py-0">
                    <x-icons.loadding />
                </p>
            </td>
        </tr>
    </table>
</div>
