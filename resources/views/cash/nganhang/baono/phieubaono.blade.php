<div class="grid grid-cols-3 items-start gap-1 pb-3">
    <div class="">
        <div class="grid grid-cols-4 items-center gap-4 pt-1">
            <label class="text-right">Mã KH</label>
            <div class="col-span-3">
                <div class="relative">
                    <input type="text"
                        class="block w-full rounded-md border-gray-300 py-0 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        list="ArDmKh-suggestions" wire:model="pMa_Kh" wire:change="updateKhachHang" />
                </div>

                @if (isset($pKh))
                    <span class="text-xs">{{ $pKh->ten_kh }}</span>
                    <span class="text-xs">{{ $pKh->dia_chi }}</span>
                    <span class="text-xs">{{ $pKh->nguoi_gd }}</span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-4 items-center gap-4 pt-1">
            <label class="text-right">Diễn giải</label>
            <input class="col-span-3 rounded-md border-gray-300 py-0 text-sm shadow-sm" wire:model="pDien_Giai" />
        </div>

        <div class="grid grid-cols-4 items-center gap-4 pt-1">
            <label class="text-right">TK có</label>
            <div class="col-span-3">
                <livewire:catalog::component.input-taikhoan wire:model="pTk_Co" />
            </div>
        </div>

        <div class="grid grid-cols-4 items-center gap-4 pt-1">
            <label class="text-right">Số CTừ</label>
            <input class="col-span-3 rounded-md border-gray-300 py-0 text-sm shadow-sm" wire:model="pSo_Ct" />
        </div>

        <div class="grid grid-cols-4 items-center gap-4 pt-1">
            <label class="text-right">Ngày CTừ</label>
            <div class="col-span-3">
                <x-input-date class="block w-full px-2 py-0 text-sm" wire:model="pNgay_Ct" />
            </div>
        </div>

        <div class="grid grid-cols-4 items-center gap-4 pt-1">
            <label class="text-right">Ngày lập</label>
            <div class="col-span-3">
                <x-input-date class="block w-full px-2 py-0 text-sm" wire:model="pNgay_Lap" />
            </div>
        </div>
    </div>

    <div>
        @isset($pCts)
            @foreach ($pCts as $ct)
            <div>
                {{ $ct->ma_ct }}
            </div>
            @endforeach
        @endisset
        {{ $pCts }}
    </div>

    <datalist id="ArDmKh-suggestions">
        @foreach ($arDmKhs as $arDmKh)
            <option value="{{ $arDmKh->ma_kh }}">{{ $arDmKh->ten_kh }}</option>
        @endforeach
    </datalist>
</div>
