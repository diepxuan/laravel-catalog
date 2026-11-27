<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Kỳ báo cáo</label>
    <div class="col-span-2">
        @livewire('catalog::component.timer', key('porptbk01-timer'))
        <x-input-error for="pNgay1" class="mt-1" />
        <x-input-error for="pNgay2" class="mt-1" />
    </div>
</div>
<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Chứng từ số</label>
    <div class="col-span-2 flex items-center gap-2">
        <input type="text" class="w-full max-w-[180px] rounded-md border-gray-300 px-2 py-1 text-sm" wire:model="pSoct1" />
        <span class="text-sm text-gray-500">-</span>
        <label class="text-sm text-gray-700">Đến số</label>
        <input type="text" class="w-full max-w-[180px] rounded-md border-gray-300 px-2 py-1 text-sm" wire:model="pSoct2" />
    </div>
</div>
<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Loại phiếu</label>
    <div class="col-span-2">
        <livewire:catalog::component.input-loai-phieu wire:model="pMa_ct" phan-he="PO" :include-all="true" />
    </div>
</div>
<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Mã NCC</label>
    <div class="col-span-2">
        <livewire:catalog::component.input-khachhang mode="nhacungcap" wire:model="pMa_kh" />
    </div>
</div>
<div class="grid grid-cols-3 items-center gap-4"><label class="text-right text-sm text-gray-700">P loại NCC1</label><div class="col-span-2"><input type="text" class="w-full max-w-[260px] rounded-md border-gray-300 px-2 py-1 text-sm" wire:model="pMa_plkh1" /></div></div>
<div class="grid grid-cols-3 items-center gap-4"><label class="text-right text-sm text-gray-700">P loại NCC2</label><div class="col-span-2"><input type="text" class="w-full max-w-[260px] rounded-md border-gray-300 px-2 py-1 text-sm" wire:model="pMa_plkh2" /></div></div>
<div class="grid grid-cols-3 items-center gap-4"><label class="text-right text-sm text-gray-700">P loại NCC3</label><div class="col-span-2"><input type="text" class="w-full max-w-[260px] rounded-md border-gray-300 px-2 py-1 text-sm" wire:model="pMa_plkh3" /></div></div>
<div class="grid grid-cols-3 items-center gap-4"><label class="text-right text-sm text-gray-700">Mã hợp đồng</label><div class="col-span-2"><input type="text" class="w-full max-w-[260px] rounded-md border-gray-300 px-2 py-1 text-sm" wire:model="pMa_hd" /></div></div>
<div class="grid grid-cols-3 items-center gap-4"><label class="text-right text-sm text-gray-700">Nhóm hợp đồng</label><div class="col-span-2"><input type="text" class="w-full max-w-[260px] rounded-md border-gray-300 px-2 py-1 text-sm" wire:model="pMa_nhhd" /></div></div>
<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Hình thức TT</label>
    <div class="col-span-2">
        <livewire:catalog::component.input-httt module-id="PO" wire:model="pMa_httt" placeholder="Chọn hình thức thanh toán" />
    </div>
</div>
<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Mã kho</label>
    <div class="col-span-2">
        <livewire:catalog::component.input-indmkho wire:model="pMa_kho" />
    </div>
</div>
<div class="grid grid-cols-3 items-center gap-4"><label class="text-right text-sm text-gray-700">Mã lô</label><div class="col-span-2"><input type="text" class="w-full max-w-[260px] rounded-md border-gray-300 px-2 py-1 text-sm" wire:model="pMa_lo" /></div></div>
<div class="grid grid-cols-3 items-center gap-4"><label class="text-right text-sm text-gray-700">Mã phí</label><div class="col-span-2"><input type="text" class="w-full max-w-[260px] rounded-md border-gray-300 px-2 py-1 text-sm" wire:model="pMa_Phi" /></div></div>
<div class="grid grid-cols-3 items-center gap-4"><label class="text-right text-sm text-gray-700">Mã SPCT</label><div class="col-span-2"><input type="text" class="w-full max-w-[260px] rounded-md border-gray-300 px-2 py-1 text-sm" wire:model="pMa_spct" /></div></div>
<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Mã vật tư</label>
    <div class="col-span-2">
        <livewire:catalog::component.input-indmvt wire:model="pMa_vt" />
    </div>
</div>
<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Mã nhóm vật tư</label>
    <div class="col-span-2">
        <livewire:catalog::component.input-indmnhvt wire:model="pMa_nhvt" />
    </div>
</div>
<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Mã ngoại tệ</label>
    <div class="col-span-2">
        <livewire:catalog::component.input-ngoaite wire:model="pMa_nt" />
    </div>
</div>
