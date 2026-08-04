<div class="po-ardmkh-form-container w-full">
    <div class="mb-4 flex flex-wrap items-center justify-between gap-4">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ $mode === 'create' ? 'Thêm nhà cung cấp mới' : 'Sửa nhà cung cấp: ' . $ma_kh }}</h2>
        <a href="{{ simbaroute('po.dict.ardmkh') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Quay lại</a>
    </div>

    <form wire:submit="save" class="mt-4 space-y-4">
        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 bg-gray-50 px-6 py-3">
                <h3 class="text-sm font-semibold text-gray-700">Thông tin cơ bản</h3>
            </div>
            <div class="grid grid-cols-1 gap-4 p-6 md:grid-cols-2">
                <label class="block">
                    <span class="text-sm font-medium text-gray-700">Mã NCC <span class="text-red-500">*</span></span>
                    <input
                        wire:model="ma_kh"
                        maxlength="20"
                        @if($mode === 'edit') readonly @endif
                        class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 @if($mode === 'edit') bg-gray-100 @endif"
                        placeholder="VD: NCC001"
                    />
                    @error('ma_kh') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="text-sm font-medium text-gray-700">Tên nhà cung cấp <span class="text-red-500">*</span></span>
                    <input
                        wire:model="ten_kh"
                        maxlength="100"
                        class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Tên nhà cung cấp"
                    />
                    @error('ten_kh') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>

                <label class="block md:col-span-2">
                    <span class="text-sm font-medium text-gray-700">Địa chỉ</span>
                    <input
                        wire:model="dia_chi"
                        maxlength="255"
                        class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Địa chỉ"
                    />
                    @error('dia_chi') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="text-sm font-medium text-gray-700">Mã số thuế</span>
                    <input
                        wire:model="ma_so_thue"
                        maxlength="20"
                        class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Mã số thuế"
                    />
                    @error('ma_so_thue') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="text-sm font-medium text-gray-700">Người giao dịch</span>
                    <input
                        wire:model="nguoi_gd"
                        maxlength="30"
                        class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Người giao dịch"
                    />
                    @error('nguoi_gd') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>
            </div>
        </div>

        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 bg-gray-50 px-6 py-3">
                <h3 class="text-sm font-semibold text-gray-700">Liên hệ và thanh toán</h3>
            </div>
            <div class="grid grid-cols-1 gap-4 p-6 md:grid-cols-2">
                <label class="block">
                    <span class="text-sm font-medium text-gray-700">Điện thoại</span>
                    <input
                        wire:model="dien_thoai"
                        maxlength="30"
                        class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Điện thoại"
                    />
                    @error('dien_thoai') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="text-sm font-medium text-gray-700">Fax</span>
                    <input
                        wire:model="fax"
                        maxlength="30"
                        class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Fax"
                    />
                    @error('fax') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="text-sm font-medium text-gray-700">Email</span>
                    <input
                        type="email"
                        wire:model="email"
                        maxlength="30"
                        class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="email@example.com"
                    />
                    @error('email') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="text-sm font-medium text-gray-700">HTTT mua</span>
                    <input
                        wire:model="ma_httt_po"
                        maxlength="20"
                        class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Lookup MA_HTTT_PO / moduleid=PO"
                    />
                    @error('ma_httt_po') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>

                <label class="block">
                    <span class="text-sm font-medium text-gray-700">TK công nợ</span>
                    <input
                        wire:model="tk_cn"
                        maxlength="20"
                        class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Tài khoản công nợ"
                    />
                    @error('tk_cn') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>
            </div>
        </div>

        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 bg-gray-50 px-6 py-3">
                <h3 class="text-sm font-semibold text-gray-700">Phân loại</h3>
            </div>
            <div class="grid grid-cols-1 gap-4 p-6 md:grid-cols-4">
                <label class="block">
                    <span class="text-sm font-medium text-gray-700">Phân loại 1</span>
                    <select wire:model="ma_plkh1" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">— Chọn —</option>
                        @foreach ($plkhOptions[1] ?? [] as $pl)
                            <option value="{{ $pl->ma_plkh }}">{{ $pl->ma_plkh }} - {{ $pl->ten_plkh }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="block">
                    <span class="text-sm font-medium text-gray-700">Phân loại 2</span>
                    <select wire:model="ma_plkh2" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">— Chọn —</option>
                        @foreach ($plkhOptions[2] ?? [] as $pl)
                            <option value="{{ $pl->ma_plkh }}">{{ $pl->ma_plkh }} - {{ $pl->ten_plkh }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="block">
                    <span class="text-sm font-medium text-gray-700">Phân loại 3</span>
                    <select wire:model="ma_plkh3" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">— Chọn —</option>
                        @foreach ($plkhOptions[3] ?? [] as $pl)
                            <option value="{{ $pl->ma_plkh }}">{{ $pl->ma_plkh }} - {{ $pl->ten_plkh }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="block">
                    <span class="text-sm font-medium text-gray-700">Nhóm NCC</span>
                    <select wire:model="ma_nhkh" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">— Chọn nhóm —</option>
                        @foreach ($nhomKhOptions as $nhom)
                            <option value="{{ $nhom->ma_nhkh }}">{{ $nhom->ma_nhkh }} - {{ $nhom->ten_nhkh }}</option>
                        @endforeach
                    </select>
                </label>
            </div>
            <div class="border-t border-gray-100 px-6 py-3">
                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input type="checkbox" wire:model="ksd" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
                    Khóa sử dụng
                </label>
            </div>
        </div>

        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 bg-gray-50 px-6 py-3">
                <h3 class="text-sm font-semibold text-gray-700">Ghi chú</h3>
            </div>
            <div class="p-6">
                <textarea
                    wire:model="ghi_chu"
                    rows="3"
                    maxlength="255"
                    class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Ghi chú"
                ></textarea>
                @error('ghi_chu') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
            </div>
        </div>

        @if ($errors->any())
            <div class="rounded border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="flex justify-end gap-2">
            <a
                href="{{ simbaroute('po.dict.ardmkh') }}"
                class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
            >
                Hủy
            </a>
            <button
                type="submit"
                class="rounded-md bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700"
            >
                Lưu
            </button>
        </div>
    </form>
</div>
