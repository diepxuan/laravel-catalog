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
    <label class="text-right text-sm text-gray-700">Tài khoản</label>
    <div class="col-span-2">
        <input type="text" class="w-full max-w-[200px] rounded-md border-gray-300 px-2 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" wire:model="pTk" />
    </div>
</div>

<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Mã phí</label>
    <div class="col-span-2">
        <input type="text" class="w-full max-w-[200px] rounded-md border-gray-300 px-2 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" wire:model="pMa_phi" />
    </div>
</div>

<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Mã bộ phận</label>
    <div class="col-span-2">
        <input type="text" class="w-full max-w-[200px] rounded-md border-gray-300 px-2 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" wire:model="pMa_bp" />
    </div>
</div>

<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Mã SPCT</label>
    <div class="col-span-2">
        <input type="text" class="w-full max-w-[200px] rounded-md border-gray-300 px-2 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" wire:model="pMa_spct" />
    </div>
</div>

@foreach ([1, 2, 3, 4] as $level)
    <div class="grid grid-cols-3 items-center gap-4">
        <label class="text-right text-sm text-gray-700">Nhóm cấp {{ $level }}</label>
        <div class="col-span-2">
            <select class="w-full max-w-[280px] rounded-md border-gray-300 px-2 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" wire:model="pGroup{{ $level }}">
                @foreach ($groupOptions as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
            <x-input-error for="pGroup{{ $level }}" class="mt-1" />
        </div>
    </div>
@endforeach
