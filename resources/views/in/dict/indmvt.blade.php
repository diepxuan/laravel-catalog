<div class="flex w-full flex-col gap-4">
    <x-head-title>{{ 'Hàng hóa, vật tư' }}</x-head-title>

    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Hàng hóa, vật tư</h2>
                <p class="text-sm text-gray-500">INDMVT - Danh mục vật tư - menu IN 14.90.02</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <button
                    type="button"
                    wire:click="refreshData"
                    class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 hover:bg-gray-50"
                >
                    Nạp lại
                </button>
                <button
                    type="button"
                    wire:click="openCreate"
                    class="rounded-md bg-blue-600 px-3 py-2 text-sm text-white hover:bg-blue-700"
                >
                    Thêm vật tư
                </button>
            </div>
        </div>
    </x-slot>

    @if ($statusMessage)
        <div class="rounded-md border border-green-200 bg-green-50 px-3 py-2 text-sm text-green-700">
            {{ $statusMessage }}
        </div>
    @endif

    @if ($errorMessage)
        <div class="rounded-md border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
            {{ $errorMessage }}
        </div>
    @endif

    @if ($loadError)
        <div class="rounded-md border border-amber-200 bg-amber-50 px-3 py-2 text-sm text-amber-700">
            Không nạp được dữ liệu Simba: {{ $loadError }}
        </div>
    @endif


    @if ($showRenameForm)
        <form id="indmvt-rename-form" wire:submit.prevent="renameItem" class="order-1 scroll-mt-4 space-y-4 rounded-lg border border-indigo-200 bg-white p-4 shadow-sm" style="order: 1">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 pb-3">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Đổi mã vật tư</h3>
                    <p class="text-xs text-gray-500">Ghi qua asDoiMa theo dictionary INDMVT / MA_VT.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" wire:click="cancelRename" class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Hủy</button>
                    <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm text-white hover:bg-indigo-700">Đổi mã</button>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <label class="block text-sm">
                    <span class="text-gray-600">Mã hiện tại</span>
                    <input type="text" wire:model="renameForm.old_ma_vt" disabled class="mt-1 w-full rounded-md border-gray-300 bg-gray-50 font-mono text-sm text-gray-700 shadow-sm" />
                    @error('old_ma_vt') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>
                <label class="block text-sm">
                    <span class="text-gray-600">Mã mới</span>
                    <input type="text" wire:model.blur="renameForm.new_ma_vt" class="mt-1 w-full rounded-md border-gray-300 font-mono text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    @error('new_ma_vt') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>
            </div>
        </form>
    @endif

    @if ($showForm)
        <form id="indmvt-form" wire:submit.prevent="save" class="order-1 scroll-mt-4 space-y-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm" style="order: 2">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 pb-3">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">{{ $isEditing ? 'Sửa vật tư' : 'Thêm vật tư' }}</h3>
                    <p class="text-xs text-gray-500">Ghi qua asIN{{ $isEditing ? 'Upd' : 'Ins' }}DMVT và đồng bộ BOM qua asIN*DMBOM.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" wire:click="cancelForm" class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Hủy</button>
                    <button type="submit" class="rounded-md bg-blue-600 px-3 py-2 text-sm text-white hover:bg-blue-700">Lưu</button>
                </div>
            </div>

            <div class="grid gap-4 lg:grid-cols-4">
                <label class="block text-sm">
                    <span class="text-gray-600">Mã vật tư</span>
                    <input type="text" wire:model.blur="form.ma_vt" @disabled($isEditing) class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                    @error('ma_vt') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>
                <label class="block text-sm lg:col-span-2">
                    <span class="text-gray-600">Tên vật tư</span>
                    <input type="text" wire:model.blur="form.ten_vt" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                    @error('ten_vt') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>
                <label class="block text-sm">
                    <span class="text-gray-600">Part no</span>
                    <input type="text" wire:model.blur="form.part_no" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                    @error('part_no') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>

                <label class="block text-sm">
                    <span class="text-gray-600">Loại</span>
                    <select wire:model="form.loai" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="1">1</option>
                        @foreach ($loaiOptions as $option)
                            <option value="{{ $option['loai'] ?? $option['ma_loai'] ?? $option['id'] ?? '' }}">
                                {{ $option['loai'] ?? $option['ma_loai'] ?? $option['id'] ?? '' }} {{ $option['ten_loai'] ?? $option['ten'] ?? '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('loai') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>
                <label class="block text-sm">
                    <span class="text-gray-600">ĐVT tồn</span>
                    <input type="text" wire:model.blur="form.dvt" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                    @error('dvt') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>
                <label class="block text-sm">
                    <span class="text-gray-600">ĐVT bán</span>
                    <input type="text" wire:model.blur="form.dvt_ban" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                    @error('dvt_ban') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>
                <label class="block text-sm">
                    <span class="text-gray-600">HS ĐVT bán</span>
                    <input type="number" step="0.0001" wire:model.blur="form.hs_dvtban" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                </label>
                <label class="block text-sm">
                    <span class="text-gray-600">ĐVT mua</span>
                    <input type="text" wire:model.blur="form.dvt_mua" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                    @error('dvt_mua') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>
                <label class="block text-sm">
                    <span class="text-gray-600">HS ĐVT mua</span>
                    <input type="number" step="0.0001" wire:model.blur="form.hs_dvtmua" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                </label>
                <label class="block text-sm">
                    <span class="text-gray-600">Giá tồn</span>
                    <select wire:model="form.gia_ton" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="1">1</option>
                        @foreach ($giaTonOptions as $option)
                            <option value="{{ $option['gia_ton'] ?? $option['ma_gia_ton'] ?? $option['id'] ?? '' }}">
                                {{ $option['gia_ton'] ?? $option['ma_gia_ton'] ?? $option['id'] ?? '' }} {{ $option['ten_gia_ton'] ?? $option['ten'] ?? '' }}
                            </option>
                        @endforeach
                    </select>
                </label>
                <label class="flex items-center gap-2 pt-7 text-sm text-gray-700">
                    <input type="checkbox" wire:model="form.ton_kho" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
                    Theo dõi tồn kho
                </label>

                <label class="block text-sm">
                    <span class="text-gray-600">Nhóm vật tư</span>
                    <input type="text" list="indmvt-nhvt-options" wire:model.blur="form.ma_nhvt" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                    @error('ma_nhvt') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>
                <label class="block text-sm">
                    <span class="text-gray-600">Kho ngầm định</span>
                    <input type="text" list="indmvt-kho-options" wire:model.blur="form.ma_kho" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                    @error('ma_kho') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>
                <label class="block text-sm">
                    <span class="text-gray-600">Vị trí</span>
                    <input type="text" wire:model.blur="form.ma_vitri" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                    @error('ma_vitri') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>
                <label class="block text-sm">
                    <span class="text-gray-600">Mã thuế</span>
                    <input type="text" wire:model.blur="form.ma_thue" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                    @error('ma_thue') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>
            </div>

            <div class="grid gap-4 lg:grid-cols-6">
                @foreach (['tk_vt' => 'TK vật tư', 'tk_dt' => 'TK doanh thu', 'tk_dtnb' => 'TK DT nội bộ', 'tk_gv' => 'TK giá vốn', 'tk_tl' => 'TK trả lại', 'tk_ck' => 'TK chiết khấu', 'tk_km' => 'TK khuyến mại', 'tk_dd' => 'TK dở dang', 'tk_cpnvl' => 'TK CPNVL'] as $field => $label)
                    <label class="block text-sm">
                        <span class="text-gray-600">{{ $label }}</span>
                        <input type="text" wire:model.blur="form.{{ $field }}" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        @error($field) <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>
                @endforeach
            </div>

            <div class="grid gap-4 lg:grid-cols-6">
                @foreach (['ma_plvt1' => 'PLVT 1', 'ma_plvt2' => 'PLVT 2', 'ma_plvt3' => 'PLVT 3', 'ma_nhvat' => 'Nhóm VAT'] as $field => $label)
                    <label class="block text-sm">
                        <span class="text-gray-600">{{ $label }}</span>
                        <input type="text" wire:model.blur="form.{{ $field }}" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        @error($field) <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>
                @endforeach
                <label class="flex items-center gap-2 pt-7 text-sm text-gray-700">
                    <input type="checkbox" wire:model="form.tinh_gt" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
                    Tính giá thành
                </label>
                <label class="flex items-center gap-2 pt-7 text-sm text-gray-700">
                    <input type="checkbox" wire:model="form.ksd" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
                    Không sử dụng
                </label>
            </div>

            <div class="grid gap-4 lg:grid-cols-8">
                @foreach (['sl_min' => 'SL min', 'sl_max' => 'SL max', 'gia_nt0' => 'Giá NT0', 'gia_nt2' => 'Giá NT2', 'ts_nk' => 'Thuế NK', 'ts_gtgt' => 'Thuế GTGT', 'ts_xk' => 'Thuế XK', 'ts_ttdb' => 'Thuế TTĐB'] as $field => $label)
                    <label class="block text-sm">
                        <span class="text-gray-600">{{ $label }}</span>
                        <input type="number" step="0.0001" wire:model.blur="form.{{ $field }}" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        @error($field) <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>
                @endforeach
            </div>

            <div class="grid gap-4 lg:grid-cols-4">
                @foreach (['nha_sx' => 'Nhà sản xuất', 'nha_pp' => 'Nhà phân phối', 'nuoc_sx' => 'Nước sản xuất'] as $field => $label)
                    <label class="block text-sm">
                        <span class="text-gray-600">{{ $label }}</span>
                        <input type="text" wire:model.blur="form.{{ $field }}" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                    </label>
                @endforeach
                <label class="block text-sm">
                    <span class="text-gray-600">Ghi chú</span>
                    <input type="text" wire:model.blur="form.ghi_chu" class="mt-1 w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                </label>
            </div>

            <div class="space-y-3 border-t border-gray-100 pt-4">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900">BOM / định mức linh kiện</h4>
                        <p class="text-xs text-gray-500">Nguồn InDmBom, khóa ma_cty + ma_vt + ma_lk.</p>
                    </div>
                    <button type="button" wire:click="addBomRow" class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Thêm dòng BOM</button>
                </div>

                <div class="overflow-x-auto rounded-md border border-gray-200">
                    <table class="min-w-full text-xs">
                        <thead class="bg-gray-50 text-left text-gray-500">
                            <tr>
                                <th class="px-2 py-2 font-medium">Mã LK</th>
                                <th class="px-2 py-2 font-medium">Tên LK</th>
                                <th class="px-2 py-2 font-medium">ĐVT</th>
                                <th class="px-2 py-2 text-right font-medium">Số lượng</th>
                                <th class="px-2 py-2 text-right font-medium">Hệ số</th>
                                <th class="px-2 py-2 font-medium">Ghi chú</th>
                                <th class="px-2 py-2 text-center font-medium">KSD</th>
                                <th class="px-2 py-2 text-right font-medium">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($bomRows as $index => $bomRow)
                                <tr wire:key="indmvt-bom-{{ $index }}">
                                    <td class="px-2 py-2 align-top">
                                        <input type="text" list="indmvt-item-options" wire:model.blur="bomRows.{{ $index }}.ma_lk" wire:change="fillBomItem({{ $index }})" class="w-36 rounded-md border-gray-300 text-xs shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                                        @error('bomRows.' . $index . '.ma_lk') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                                    </td>
                                    <td class="px-2 py-2 align-top">
                                        <input type="text" wire:model.blur="bomRows.{{ $index }}.ten_lk" class="w-64 rounded-md border-gray-300 text-xs shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                                    </td>
                                    <td class="px-2 py-2 align-top">
                                        <input type="text" wire:model.blur="bomRows.{{ $index }}.dvt" class="w-24 rounded-md border-gray-300 text-xs shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                                    </td>
                                    <td class="px-2 py-2 align-top text-right">
                                        <input type="number" step="0.0001" wire:model.blur="bomRows.{{ $index }}.so_luong" class="w-28 rounded-md border-gray-300 text-right text-xs shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                                        @error('bomRows.' . $index . '.so_luong') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                                    </td>
                                    <td class="px-2 py-2 align-top text-right">
                                        <input type="number" step="0.0001" wire:model.blur="bomRows.{{ $index }}.he_so" class="w-24 rounded-md border-gray-300 text-right text-xs shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                                    </td>
                                    <td class="px-2 py-2 align-top">
                                        <input type="text" wire:model.blur="bomRows.{{ $index }}.ghi_chu" class="w-52 rounded-md border-gray-300 text-xs shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                                    </td>
                                    <td class="px-2 py-2 text-center align-top">
                                        <input type="checkbox" wire:model="bomRows.{{ $index }}.ksd" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
                                    </td>
                                    <td class="px-2 py-2 text-right align-top">
                                        <button type="button" wire:click="removeBomRow({{ $index }})" class="rounded bg-red-100 px-2 py-1 text-xs text-red-700 hover:bg-red-200">Xóa dòng</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-3 py-5 text-center text-gray-500">Chưa có dòng BOM.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    @endif

    <div class="order-2 flex flex-wrap items-center gap-3" style="order: 3">
        <div class="relative w-full max-w-xl">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Tìm theo mã, tên, nhóm, ĐVT, tài khoản vật tư..."
                class="w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
            />
            @if ($search)
                <button
                    type="button"
                    wire:click="$set('search', '')"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 hover:text-gray-600"
                >
                    Xóa
                </button>
            @endif
        </div>
        <span class="text-xs text-gray-500">{{ count($displayRows) }} / {{ count($rows) }} vật tư</span>
        @if ($selectedMaVt)
            <button
                type="button"
                wire:click="openEdit"
                class="rounded-md bg-yellow-100 px-3 py-2 text-sm text-yellow-800 hover:bg-yellow-200"
            >
                Sửa {{ $selectedMaVt }}
            </button>
            <button
                type="button"
                wire:click="openRename"
                class="rounded-md bg-indigo-100 px-3 py-2 text-sm text-indigo-800 hover:bg-indigo-200"
            >
                Đổi mã {{ $selectedMaVt }}
            </button>
        @endif
    </div>

    <div class="order-3 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm" style="order: 4">
        <div class="max-h-[calc(100vh-280px)] overflow-y-auto">
            <table class="min-w-full text-left text-xs">
                <thead class="sticky top-0 z-10 bg-gray-50 text-gray-500">
                    <tr>
                        <th class="border-b border-gray-200 px-2 py-2 font-medium">#</th>
                        <th class="border-b border-gray-200 px-2 py-2 font-medium">Mã VT</th>
                        <th class="border-b border-gray-200 px-2 py-2 font-medium">Tên vật tư</th>
                        <th class="border-b border-gray-200 px-2 py-2 font-medium">ĐVT</th>
                        <th class="border-b border-gray-200 px-2 py-2 font-medium">Nhóm</th>
                        <th class="border-b border-gray-200 px-2 py-2 font-medium">Kho</th>
                        <th class="border-b border-gray-200 px-2 py-2 font-medium">Vị trí</th>
                        <th class="border-b border-gray-200 px-2 py-2 font-medium">TK VT</th>
                        <th class="border-b border-gray-200 px-2 py-2 font-medium">TK DT</th>
                        <th class="border-b border-gray-200 px-2 py-2 font-medium">TK GV</th>
                        <th class="border-b border-gray-200 px-2 py-2 text-center font-medium">Tồn</th>
                        <th class="border-b border-gray-200 px-2 py-2 font-medium">Loại</th>
                        <th class="border-b border-gray-200 px-2 py-2 font-medium">Giá tồn</th>
                        <th class="border-b border-gray-200 px-2 py-2 text-center font-medium">KSD</th>
                        <th class="border-b border-gray-200 px-2 py-2 text-right font-medium">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($displayRows as $row)
                        @php($maVt = (string) ($row['ma_vt'] ?? ''))
                        <tr
                            wire:key="in-dict-indmvt-{{ $maVt }}"
                            wire:click="selectItem(@js($maVt))"
                            class="cursor-pointer hover:bg-sky-50 {{ $selectedMaVt === $maVt ? 'bg-blue-50' : '' }}"
                        >
                            <td class="px-2 py-2 text-right text-gray-400">{{ $loop->iteration }}</td>
                            <td class="whitespace-nowrap px-2 py-2 font-mono font-semibold text-gray-900">{{ $maVt }}</td>
                            <td class="min-w-64 px-2 py-2 text-gray-800">{{ $row['ten_vt'] ?? '' }}</td>
                            <td class="whitespace-nowrap px-2 py-2 font-mono">{{ $row['dvt'] ?? '' }}</td>
                            <td class="whitespace-nowrap px-2 py-2 font-mono">{{ $row['ma_nhvt'] ?? '' }}</td>
                            <td class="whitespace-nowrap px-2 py-2 font-mono">{{ $row['ma_kho'] ?? '' }}</td>
                            <td class="whitespace-nowrap px-2 py-2 font-mono">{{ $row['ma_vitri'] ?? '' }}</td>
                            <td class="whitespace-nowrap px-2 py-2 font-mono">{{ $row['tk_vt'] ?? '' }}</td>
                            <td class="whitespace-nowrap px-2 py-2 font-mono">{{ $row['tk_dt'] ?? '' }}</td>
                            <td class="whitespace-nowrap px-2 py-2 font-mono">{{ $row['tk_gv'] ?? '' }}</td>
                            <td class="px-2 py-2 text-center">{{ (int) ($row['ton_kho'] ?? 0) ? 'Có' : 'Không' }}</td>
                            <td class="whitespace-nowrap px-2 py-2 font-mono">{{ $row['loai'] ?? '' }}</td>
                            <td class="whitespace-nowrap px-2 py-2 font-mono">{{ $row['gia_ton'] ?? '' }}</td>
                            <td class="px-2 py-2 text-center">{{ (int) ($row['ksd'] ?? 0) ? 'Có' : '' }}</td>
                            <td class="whitespace-nowrap px-2 py-2 text-right" wire:click.stop>
                                @if ($deleteMaVt === $maVt)
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" wire:click="deleteItem" class="rounded bg-red-600 px-2 py-1 text-xs text-white hover:bg-red-700">Xác nhận</button>
                                        <button type="button" wire:click="cancelDelete" class="rounded bg-gray-100 px-2 py-1 text-xs text-gray-700 hover:bg-gray-200">Hủy</button>
                                    </div>
                                @else
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" wire:click="openEdit(@js($maVt))" class="rounded bg-yellow-100 px-2 py-1 text-xs text-yellow-700 hover:bg-yellow-200">Sửa</button>
                                        <button type="button" wire:click="openRename(@js($maVt))" class="rounded bg-indigo-100 px-2 py-1 text-xs text-indigo-700 hover:bg-indigo-200">Đổi mã</button>
                                        <button type="button" wire:click="confirmDelete(@js($maVt))" class="rounded bg-red-100 px-2 py-1 text-xs text-red-700 hover:bg-red-200">Xóa</button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="15" class="px-3 py-8 text-center text-sm text-gray-500">Không có vật tư phù hợp.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <datalist id="indmvt-nhvt-options">
        @foreach ($nhvtOptions as $option)
            <option value="{{ $option['ma_nhvt'] ?? '' }}">{{ $option['ten_nhvt'] ?? '' }}</option>
        @endforeach
    </datalist>

    <datalist id="indmvt-kho-options">
        @foreach ($khoOptions as $option)
            <option value="{{ $option['ma_kho'] ?? '' }}">{{ $option['ten_kho'] ?? '' }}</option>
        @endforeach
    </datalist>

    <datalist id="indmvt-item-options">
        @foreach ($rows as $row)
            <option value="{{ $row['ma_vt'] ?? '' }}">{{ $row['ten_vt'] ?? '' }}</option>
        @endforeach
    </datalist>

    <script>
        document.addEventListener('livewire:init', () => {
            if (window.__indmvtListenersRegistered) {
                return;
            }
            window.__indmvtListenersRegistered = true;

            Livewire.on('indmvt-form-opened', () => {
                requestAnimationFrame(() => {
                    document.getElementById('indmvt-form')?.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start',
                    });
                });
            });

            Livewire.on('indmvt-rename-opened', () => {
                requestAnimationFrame(() => {
                    document.getElementById('indmvt-rename-form')?.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start',
                    });
                });
            });
        });
    </script>
</div>
