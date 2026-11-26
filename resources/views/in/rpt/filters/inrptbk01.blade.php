<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Năm</label>
    <div class="col-span-2">
        <input type="number" min="1990" max="2100"
            class="w-full max-w-[120px] rounded-md border-gray-300 px-2 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
            wire:model="pNam" />
        <x-input-error for="pNam" class="mt-1" />
    </div>
</div>

<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Tháng</label>
    <div class="col-span-2">
        <select class="w-full max-w-[120px] rounded-md border-gray-300 px-2 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" wire:model="pThang">
            @for ($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}">{{ $m }}</option>
            @endfor
        </select>
        <x-input-error for="pThang" class="mt-1" />
    </div>
</div>

<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Mã vật tư</label>
    <div class="col-span-2">
        <livewire:catalog::component.input-indmvt wire:model="pMa_vt" />
        <x-input-error for="pMa_vt" class="mt-1" />
    </div>
</div>

<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Mã kho</label>
    <div class="col-span-2">
        <livewire:catalog::component.input-indmkho wire:model="pMa_kho" />
        <x-input-error for="pMa_kho" class="mt-1" />
    </div>
</div>
