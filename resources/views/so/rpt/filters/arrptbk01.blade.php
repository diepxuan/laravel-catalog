<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Từ ngày</label>
    <div class="col-span-2"><x-input-date class="w-full max-w-[200px] px-2 py-1 text-sm" wire:model="pNgay1" /></div>
</div>
<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Đến ngày</label>
    <div class="col-span-2"><x-input-date class="w-full max-w-[200px] px-2 py-1 text-sm" wire:model="pNgay2" /></div>
</div>
<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Tài khoản</label>
    <div class="col-span-2"><livewire:catalog::component.input-taikhoan wire:model="pTk" /></div>
</div>
<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Mã khách hàng</label>
    <div class="col-span-2"><livewire:catalog::component.input-khachhang wire:model="pMa_kh" /></div>
</div>
