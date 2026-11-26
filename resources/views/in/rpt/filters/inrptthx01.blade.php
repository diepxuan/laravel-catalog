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
    <label class="text-right text-sm text-gray-700">Chứng từ số</label>
    <div class="col-span-2 flex items-center gap-2">
        <input type="text" class="w-full max-w-[140px] rounded-md border-gray-300 px-2 py-1 text-sm" wire:model="pCtu1" placeholder="từ số" />
        <span class="text-sm text-gray-500">đến</span>
        <input type="text" class="w-full max-w-[140px] rounded-md border-gray-300 px-2 py-1 text-sm" wire:model="pCtu2" />
    </div>
</div>

<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Mã khách hàng</label>
    <div class="col-span-2"><livewire:catalog::component.input-khachhang wire:model="pMa_kh" /></div>
</div>

<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Mã vật tư</label>
    <div class="col-span-2"><livewire:catalog::component.input-indmvt wire:model="pMa_vt" /></div>
</div>

<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Kho xuất</label>
    <div class="col-span-2"><livewire:catalog::component.input-indmkho wire:model="pMa_kho_xuat" /></div>
</div>

<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Kho nhập</label>
    <div class="col-span-2"><livewire:catalog::component.input-indmkho wire:model="pMa_kho_nhap" /></div>
</div>

<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">TK vật tư</label>
    <div class="col-span-2">
        <input type="text" class="w-full max-w-[200px] rounded-md border-gray-300 px-2 py-1 text-sm" wire:model="pTK_VT" />
    </div>
</div>

<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Mã hợp đồng</label>
    <div class="col-span-2">
        <input type="text" class="w-full max-w-[200px] rounded-md border-gray-300 px-2 py-1 text-sm" wire:model="pMa_Hd" />
    </div>
</div>

<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Mã ngoại tệ</label>
    <div class="col-span-2">
        <input type="text" class="w-full max-w-[120px] rounded-md border-gray-300 px-2 py-1 text-sm" wire:model="pMa_NT" />
    </div>
</div>

<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Đơn vị tính</label>
    <div class="col-span-2">
        <select class="w-full max-w-[220px] rounded-md border-gray-300 px-2 py-1 text-sm" wire:model="pDVT">
            <option value="0">Đơn vị gốc</option>
            <option value="1">Đơn vị nhỏ nhất</option>
            <option value="2">Đơn vị lớn nhất</option>
        </select>
    </div>
</div>

<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Ngoại tệ</label>
    <div class="col-span-2">
        <select class="w-full max-w-[220px] rounded-md border-gray-300 px-2 py-1 text-sm" wire:model="pNgoai_te">
            <option value="0">Theo VND</option>
            <option value="1">Theo ngoại tệ</option>
        </select>
    </div>
</div>
