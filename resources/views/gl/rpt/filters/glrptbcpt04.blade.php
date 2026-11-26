<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Năm</label>
    <div class="col-span-2">
        <input type="number" min="1990" max="2100"
            class="w-full max-w-[120px] rounded-md border-gray-300 px-2 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
            wire:model="pNam" />
        <x-input-error for="pNam" class="mt-1" />
    </div>
</div>

<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Từ tháng</label>
    <div class="col-span-2">
        <select class="w-full max-w-[120px] rounded-md border-gray-300 px-2 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" wire:model="pThang1">
            @for ($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}">{{ $m }}</option>
            @endfor
        </select>
        <x-input-error for="pThang1" class="mt-1" />
    </div>
</div>

<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Đến tháng</label>
    <div class="col-span-2">
        <select class="w-full max-w-[120px] rounded-md border-gray-300 px-2 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" wire:model="pThang2">
            @for ($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}">{{ $m }}</option>
            @endfor
        </select>
        <x-input-error for="pThang2" class="mt-1" />
    </div>
</div>

<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Kỳ báo cáo</label>
    <div class="col-span-2">
        <select class="w-full max-w-[200px] rounded-md border-gray-300 px-2 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" wire:model="pQuyThang">
            @foreach ($quyThangOptions as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error for="pQuyThang" class="mt-1" />
    </div>
</div>

<div class="grid grid-cols-3 items-center gap-4">
    <label class="text-right text-sm text-gray-700">Mã bộ phận</label>
    <div class="col-span-2">
        <input type="text" class="w-full max-w-[200px] rounded-md border-gray-300 px-2 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" wire:model="pMa_bp" />
        <x-input-error for="pMa_bp" class="mt-1" />
    </div>
</div>
