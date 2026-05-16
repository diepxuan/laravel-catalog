<div class="w-full">
    <x-head-title>{{ 'Phân loại khách hàng - ' . ('create' === $mode ? 'Thêm' : 'Sửa') }}</x-head-title>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">{{ 'create' === $mode ? 'Thêm phân loại khách hàng' : 'Sửa phân loại khách hàng' }}</h2>
            </div>
            <a href="{{ route('ar.phanloaikhachhang') }}" class="rounded-md bg-gray-500 px-4 py-2 text-sm text-white hover:bg-gray-600">← Quay lại</a>
        </div>
    </x-slot>
    <div class="mt-6 max-w-2xl">
        <div class="rounded-lg border border-gray-200 bg-white shadow-sm p-6">
            <form wire:submit="submit" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Mã phân loại <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="pMa_Plkh" {{ 'edit' === $mode ? 'readonly' : '' }} class="mt-1 block w-full rounded-md border-gray-300 text-sm {{ 'edit' === $mode ? 'bg-gray-100' : '' }}" />
                    @error('pMa_Plkh')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tên phân loại <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="pTen_Plkh" class="mt-1 block w-full rounded-md border-gray-300 text-sm" />
                    @error('pTen_Plkh')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Loại <span class="text-red-500">*</span></label>
                    <select wire:model="pLoai" class="mt-1 block w-full rounded-md border-gray-300 text-sm">
                        <option value="1">PL1 - Phân loại chính</option>
                        <option value="2">PL2 - Phân loại phụ</option>
                        <option value="3">PL3 - Phân loại bổ sung</option>
                    </select>
                    @error('pLoai')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Ghi chú</label>
                    <textarea wire:model="pGhi_Chu" rows="3" class="mt-1 block w-full rounded-md border-gray-300 text-sm"></textarea>
                    @error('pGhi_Chu')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-center">
                    <input id="pKsd" type="checkbox" wire:model="pKsd" class="h-4 w-4 rounded border-gray-300 text-blue-600" />
                    <label for="pKsd" class="ml-2 text-sm text-gray-700">Khóa sử dụng</label>
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('ar.phanloaikhachhang') }}" class="rounded-md bg-white px-4 py-2 text-sm text-gray-700 ring-1 ring-gray-300 hover:bg-gray-50">Hủy</a>
                    <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">{{ 'create' === $mode ? 'Thêm' : 'Lưu' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
