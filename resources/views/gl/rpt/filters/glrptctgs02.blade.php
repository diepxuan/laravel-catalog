<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Từ ngày</label>
    <div class="col-span-2"><x-input-date class="w-full max-w-[200px] px-2 py-1 text-sm" wire:model="pNgay_ct1" /></div>
</div>
<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Đến ngày</label>
    <div class="col-span-2"><x-input-date class="w-full max-w-[200px] px-2 py-1 text-sm" wire:model="pNgay_ct2" /></div>
</div>
<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Số chứng từ</label>
    <div class="col-span-2 flex items-center gap-2">
        <input type="text" class="w-full max-w-[140px] rounded-md border-gray-300 px-2 py-1 text-sm" wire:model="pCtu1" />
        <span class="text-sm text-gray-500">đến</span>
        <input type="text" class="w-full max-w-[140px] rounded-md border-gray-300 px-2 py-1 text-sm" wire:model="pCtu2" />
    </div>
</div>
<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Mã ngoại tệ</label>
    <div class="col-span-2"></div>
</div>
