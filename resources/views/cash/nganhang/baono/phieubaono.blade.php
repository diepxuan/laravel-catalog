<div class="grid grid-cols-2 items-start gap-1 pt-1">
    <div col-1>
        <div class="grid grid-cols-4 items-center gap-4 pt-1">
            <label class="text-right">Mã khách hàng</label>
            <div class="col-span-3">
                <livewire:catalog::component.input-khachhang wire:model="pMa_Kh" />
            </div>
        </div>

        <div class="grid grid-cols-4 items-center gap-4 pt-1">
            <label class="text-right">Diễn giải</label>
            <input class="col-span-3 rounded-md border-gray-300 py-1 shadow-sm" wire:model="pDien_Giai" />
        </div>

        <div class="grid grid-cols-4 items-center gap-4 pt-1">
            <label class="text-right">TK có</label>
            <div class="col-span-3">
                <livewire:catalog::component.input-taikhoan wire:model="pTk_Co" />
            </div>
        </div>
    </div>

    <div col-2>

        <div class="grid grid-cols-4 items-center gap-4 pt-1">
            <label class="text-right">Ngày Chứng Từ</label>
            <div class="col-span-3">
                <x-input-date class="block w-full px-2 py-1" wire:model="pNgay_Ct" />
            </div>
        </div>

        <div class="grid grid-cols-4 items-center gap-4 pt-1">
            <label class="text-right">Số Chứng Từ</label>
            <input class="col-span-3 rounded-md border-gray-300 py-1 shadow-sm" wire:model="pSo_Ct" />
        </div>

        <div class="grid grid-cols-4 items-center gap-4 pt-1">
            <label class="text-right">Ngày lập</label>
            <div class="col-span-3">
                <x-input-date class="block w-full px-2 py-1" wire:model="pNgay_Lap" />
            </div>
        </div>
    </div>
</div>
