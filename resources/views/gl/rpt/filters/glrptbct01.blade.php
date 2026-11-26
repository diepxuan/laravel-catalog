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
    <label class="text-right text-sm text-gray-700">Thuế suất</label>
    <div class="col-span-2">
        <input type="number" class="w-full max-w-[120px] rounded-md border-gray-300 px-2 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" wire:model="pThue_suat" />
        <x-input-error for="pThue_suat" class="mt-1" />
    </div>
</div>

<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Tài khoản thuế</label>
    <div class="col-span-2">
        <input type="text" class="w-full max-w-[200px] rounded-md border-gray-300 px-2 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" wire:model="pTk_thue" />
    </div>
</div>

<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Tài khoản đ/ư</label>
    <div class="col-span-2">
        <input type="text" class="w-full max-w-[200px] rounded-md border-gray-300 px-2 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" wire:model="pTk_du" />
    </div>
</div>

<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Mã bộ phận</label>
    <div class="col-span-2">
        <input type="text" class="w-full max-w-[200px] rounded-md border-gray-300 px-2 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" wire:model="pMa_bp" />
    </div>
</div>
