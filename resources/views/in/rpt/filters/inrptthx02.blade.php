<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Từ ngày</label>
    <div class="col-span-2"><x-input-date class="w-full max-w-[200px] px-2 py-1 text-sm" wire:model="pNgay1" /></div>
</div>
<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Đến ngày</label>
    <div class="col-span-2"><x-input-date class="w-full max-w-[200px] px-2 py-1 text-sm" wire:model="pNgay2" /></div>
</div>
<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Chứng từ</label>
    <div class="col-span-2 flex items-center gap-2">
        <input type="text" class="w-full max-w-[140px] rounded-md border-gray-300 px-2 py-1 text-sm" wire:model="pCtu1" />
        <span class="text-sm text-gray-500">đến</span>
        <input type="text" class="w-full max-w-[140px] rounded-md border-gray-300 px-2 py-1 text-sm" wire:model="pCtu2" />
    </div>
</div>
<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Nhóm chỉ tiêu 1</label>
    <div class="col-span-2">
        <select class="w-full max-w-[220px] rounded-md border-gray-300 px-2 py-1 text-sm" wire:model="pGroup">
            <option value="">-- không nhóm --</option>
            <option value="TK">Theo tài khoản</option>
            <option value="ma_kh">Theo khách hàng</option>
            <option value="ma_vt">Theo vật tư</option>
            <option value="ma_kho">Theo kho</option>
        </select>
    </div>
</div>
<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Chỉ tiêu 2</label>
    <div class="col-span-2">
        <select class="w-full max-w-[220px] rounded-md border-gray-300 px-2 py-1 text-sm" wire:model="pDetail">
            <option value="">-- không --</option>
            <option value="TK">Theo tài khoản</option>
            <option value="ma_kh">Theo khách hàng</option>
            <option value="ma_vt">Theo vật tư</option>
        </select>
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
    <label class="text-right text-sm text-gray-700">Mã kho xuất</label>
    <div class="col-span-2"><livewire:catalog::component.input-indmkho wire:model="pMa_kho" /></div>
</div>
<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">TK vật tư</label>
    <div class="col-span-2"><input type="text" class="w-full max-w-[200px] rounded-md border-gray-300 px-2 py-1 text-sm" wire:model="pTK_VT" /></div>
</div>
<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Mã ngoại tệ</label>
    <div class="col-span-2"><input type="text" class="w-full max-w-[120px] rounded-md border-gray-300 px-2 py-1 text-sm" wire:model="pMa_NT" /></div>
</div>
