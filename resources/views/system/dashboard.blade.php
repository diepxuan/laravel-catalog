<div wire:init="loadData" class="relative mx-4 my-2">

    <h1 class="mt-1 text-2xl font-medium text-gray-900">
        {{ $system->ten_cty }}
    </h1>

    <table class="table table-sm align-middle table-hover mt-6 text-gray-500 leading-relaxed">
        <tr>
            <td>{{ 'Khoá số liệu:' }}</td>
            <td>
                <input type="date" placeholder="dd/mm/yyyy" wire:model="ngayKhoaSo" wire:change="updateKhoaSo()" />
            </td>
        </tr>
    </table>
</div>
