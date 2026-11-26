<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Năm</label>
    <div class="col-span-2"><input type="number" class="w-full max-w-[120px] rounded-md border-gray-300 px-2 py-1 text-sm" wire:model="pNam" /></div>
</div>
<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Tháng</label>
    <div class="col-span-2">
        <select class="w-full max-w-[120px] rounded-md border-gray-300 px-2 py-1 text-sm" wire:model="pThang">
            @for ($m = 1; $m <= 12; $m++)<option value="{{ $m }}">{{ $m }}</option>@endfor
        </select>
    </div>
</div>
<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Số kỳ</label>
    <div class="col-span-2"><input type="number" class="w-full max-w-[120px] rounded-md border-gray-300 px-2 py-1 text-sm" wire:model="pSo_ky" /></div>
</div>
<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Loại</label>
    <div class="col-span-2"><input type="text" class="w-full max-w-[200px] rounded-md border-gray-300 px-2 py-1 text-sm" wire:model="pLoai" /></div>
</div>
<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Mã chứng từ</label>
    <div class="col-span-2"><input type="text" class="w-full max-w-[200px] rounded-md border-gray-300 px-2 py-1 text-sm" wire:model="pMa_ct" /></div>
</div>
<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Mã ngoại tệ</label>
    <div class="col-span-2"><input type="text" class="w-full max-w-[120px] rounded-md border-gray-300 px-2 py-1 text-sm" wire:model="pMa_nt" /></div>
</div>
