<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Từ ngày</label>
    <div class="col-span-2">
        <x-input-date class="w-full max-w-[200px] px-2 py-1 text-sm" wire:model="pNgay1" />
        <x-input-error for="pNgay1" class="mt-1" />
    </div>
</div>

<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Đến ngày</label>
    <div class="col-span-2">
        <x-input-date class="w-full max-w-[200px] px-2 py-1 text-sm" wire:model="pNgay2" />
        <x-input-error for="pNgay2" class="mt-1" />
    </div>
</div>

<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Mã ngoại tệ</label>
    <div class="col-span-2">
        <input type="text" class="w-full max-w-[120px] rounded-md border-gray-300 px-2 py-1 text-sm" wire:model="pMa_Nt" />
    </div>
</div>
