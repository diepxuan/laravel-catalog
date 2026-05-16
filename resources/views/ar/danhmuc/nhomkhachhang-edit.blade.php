<div class="nhomkhachhang-edit-container w-full">
    <x-head-title>{{ 'Nhóm khách hàng - ' . ('create' === $mode ? 'Thêm mới' : 'Sửa') }}</x-head-title>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ 'create' === $mode ? 'Thêm nhóm khách hàng' : 'Sửa nhóm khách hàng' }}
                </h2>
                <p class="text-sm text-gray-500">
                    {{ 'create' === $mode ? 'Thêm mới nhóm khách hàng AR' : 'Cập nhật thông tin nhóm khách hàng' }}
                </p>
            </div>
            <a
                href="{{ route('ar.nhomkhachhang') }}"
                class="rounded-md bg-gray-500 px-4 py-2 text-sm text-white hover:bg-gray-600"
            >
                ← Quay lại
            </a>
        </div>
    </x-slot>

    <div class="mt-6 max-w-2xl">
        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
            <form wire:submit="submit" class="p-6">
                <div class="space-y-4">
                    {{-- Mã nhóm --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Mã nhóm <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            wire:model="pMa_Nhkh"
                            {{ 'edit' === $mode ? 'readonly' : '' }}
                            placeholder="VD: NH01"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm {{ 'edit' === $mode ? 'bg-gray-100' : '' }}"
                        />
                        @error('pMa_Nhkh')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tên nhóm --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Tên nhóm <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            wire:model="pTen_Nhkh"
                            placeholder="VD: Khách hàng lẻ"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        />
                        @error('pTen_Nhkh')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Ghi chú --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Ghi chú
                        </label>
                        <textarea
                            wire:model="pGhi_Chu"
                            rows="3"
                            placeholder="Ghi chú thêm (tùy chọn)"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        ></textarea>
                        @error('pGhi_Chu')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Khóa sử dụng --}}
                    <div class="flex items-center">
                        <input
                            id="pKsd"
                            type="checkbox"
                            wire:model="pKsd"
                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        />
                        <label for="pKsd" class="ml-2 block text-sm text-gray-700">
                            Khóa sử dụng
                        </label>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-6 flex justify-end gap-3">
                    <a
                        href="{{ route('ar.nhomkhachhang') }}"
                        class="rounded-md bg-white px-4 py-2 text-sm text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                    >
                        Hủy
                    </a>
                    <button
                        type="submit"
                        class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                    >
                        {{ 'create' === $mode ? 'Thêm' : 'Lưu' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
