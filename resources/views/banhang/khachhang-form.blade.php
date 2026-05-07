<div class="khachhang-form-container w-full">
    <x-head-title>{{ $mode === 'create' ? 'Thêm khách hàng' : 'Sửa khách hàng' }}</x-head-title>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a
                href="{{ route('ar.khachhang') }}"
                class="rounded-md bg-gray-200 px-3 py-1 text-sm text-gray-700 hover:bg-gray-300"
            >
                ← Quay lại
            </a>
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ $mode === 'create' ? 'Thêm khách hàng mới' : 'Sửa khách hàng: ' . $ma_kh }}
                </h2>
            </div>
        </div>
    </x-slot>

    {{-- Flash Messages --}}
    @if (session()->has('message'))
        <div class="mt-4 rounded-md bg-green-50 p-3 text-sm text-green-800">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit="save" class="mt-4">
        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="grid grid-cols-2 gap-4 p-6">
                {{-- Mã khách hàng --}}
                <div class="col-span-1">
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Mã khách hàng <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        wire:model="ma_kh"
                        maxlength="50"
                        class="w-full rounded-md border-gray-300 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 @if ($mode === 'edit') bg-gray-100 @endif"
                        @if ($mode === 'edit') readonly @endif
                        placeholder="VD: KH001"
                    />
                    @error('ma_kh')
                        <span class="mt-1 block text-xs text-red-600">{{ $message }}</span>
                    @enderror
                    @if ($mode === 'edit' && $hasTransactions)
                        <span class="mt-1 block text-xs text-amber-600">
                            ⚠ Không thể sửa mã — đã có giao dịch liên quan.
                        </span>
                    @endif
                </div>

                {{-- Tên khách hàng --}}
                <div class="col-span-1">
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Tên khách hàng <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        wire:model="ten_kh"
                        maxlength="200"
                        class="w-full rounded-md border-gray-300 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Tên khách hàng"
                    />
                    @error('ten_kh')
                        <span class="mt-1 block text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Địa chỉ --}}
                <div class="col-span-2">
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Địa chỉ
                    </label>
                    <input
                        type="text"
                        wire:model="dia_chi"
                        maxlength="500"
                        class="w-full rounded-md border-gray-300 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Địa chỉ"
                    />
                    @error('dia_chi')
                        <span class="mt-1 block text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Mã số thuế --}}
                <div class="col-span-1">
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Mã số thuế
                    </label>
                    <input
                        type="text"
                        wire:model="ma_so_thue"
                        maxlength="50"
                        class="w-full rounded-md border-gray-300 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Mã số thuế"
                    />
                    @error('ma_so_thue')
                        <span class="mt-1 block text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Điện thoại --}}
                <div class="col-span-1">
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Điện thoại
                    </label>
                    <input
                        type="text"
                        wire:model="dien_thoai"
                        maxlength="50"
                        class="w-full rounded-md border-gray-300 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Điện thoại"
                    />
                    @error('dien_thoai')
                        <span class="mt-1 block text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Fax --}}
                <div class="col-span-1">
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Fax
                    </label>
                    <input
                        type="text"
                        wire:model="fax"
                        maxlength="50"
                        class="w-full rounded-md border-gray-300 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Fax"
                    />
                    @error('fax')
                        <span class="mt-1 block text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="col-span-1">
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Email
                    </label>
                    <input
                        type="email"
                        wire:model="email"
                        maxlength="100"
                        class="w-full rounded-md border-gray-300 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="email@example.com"
                    />
                    @error('email')
                        <span class="mt-1 block text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Mã ngoại tệ --}}
                <div class="col-span-1">
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Mã ngoại tệ
                    </label>
                    <input
                        type="text"
                        wire:model="ma_nt"
                        maxlength="10"
                        class="w-full rounded-md border-gray-300 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="VND"
                    />
                    @error('ma_nt')
                        <span class="mt-1 block text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Tài khoản công nợ --}}
                <div class="col-span-1">
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Tài khoản công nợ
                    </label>
                    <input
                        type="text"
                        wire:model="tk_cn"
                        maxlength="20"
                        class="w-full rounded-md border-gray-300 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="TK công nợ"
                    />
                    @error('tk_cn')
                        <span class="mt-1 block text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Nhóm khách hàng --}}
                <div class="col-span-1">
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Nhóm khách hàng
                    </label>
                    <select
                        wire:model="ma_nhkh"
                        class="w-full rounded-md border-gray-300 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option value="">-- Chọn nhóm --</option>
                        @foreach ($nhomKhOptions as $nh)
                            <option value="{{ $nh->ma_nhkh }}">{{ $nh->ma_nhkh }} - {{ $nh->ten_nhkh }}</option>
                        @endforeach
                    </select>
                    @error('ma_nhkh')
                        <span class="mt-1 block text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Phân loại KH 1 --}}
                <div class="col-span-1">
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Phân loại KH 1
                    </label>
                    <select
                        wire:model="ma_plkh1"
                        class="w-full rounded-md border-gray-300 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option value="">-- Chọn --</option>
                        @foreach ($plkhOptions[1] ?? [] as $pl)
                            <option value="{{ $pl->ma_plkh }}">{{ $pl->ma_plkh }} - {{ $pl->ten_plkh }}</option>
                        @endforeach
                    </select>
                    @error('ma_plkh1')
                        <span class="mt-1 block text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Phân loại KH 2 --}}
                <div class="col-span-1">
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Phân loại KH 2
                    </label>
                    <select
                        wire:model="ma_plkh2"
                        class="w-full rounded-md border-gray-300 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option value="">-- Chọn --</option>
                        @foreach ($plkhOptions[2] ?? [] as $pl)
                            <option value="{{ $pl->ma_plkh }}">{{ $pl->ma_plkh }} - {{ $pl->ten_plkh }}</option>
                        @endforeach
                    </select>
                    @error('ma_plkh2')
                        <span class="mt-1 block text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Phân loại KH 3 --}}
                <div class="col-span-1">
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Phân loại KH 3
                    </label>
                    <select
                        wire:model="ma_plkh3"
                        class="w-full rounded-md border-gray-300 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option value="">-- Chọn --</option>
                        @foreach ($plkhOptions[3] ?? [] as $pl)
                            <option value="{{ $pl->ma_plkh }}">{{ $pl->ma_plkh }} - {{ $pl->ten_plkh }}</option>
                        @endforeach
                    </select>
                    @error('ma_plkh3')
                        <span class="mt-1 block text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Là khách hàng --}}
                <div class="col-span-1">
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Là khách hàng
                    </label>
                    <div class="flex items-center pt-2">
                        <input
                            type="checkbox"
                            wire:model="isKh"
                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        />
                        <span class="ml-2 text-sm text-gray-600">Đánh dấu là khách hàng</span>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex justify-end gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4">
                <a
                    href="{{ route('ar.khachhang') }}"
                    class="rounded-md bg-gray-200 px-4 py-2 text-sm text-gray-700 hover:bg-gray-300"
                >
                    Hủy
                </a>
                <button
                    type="submit"
                    class="rounded-md bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700"
                >
                    {{ $mode === 'create' ? 'Thêm mới' : 'Cập nhật' }}
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('success', (event) => {
            window.dispatchEvent(new CustomEvent('toast-success', { detail: { message: event.message } }));
        });
        Livewire.on('error', (event) => {
            window.dispatchEvent(new CustomEvent('toast-error', { detail: { message: event.message } }));
        });
    });
</script>
@endpush
