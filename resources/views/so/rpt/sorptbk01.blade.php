<div>
    <x-nav-tabs default-tab="filter">
        <x-slot:nav>
            <li class="mr-2">
                <a href="#" x-on:click.prevent="activeTab = 'filter'"
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'filter', 'border-transparent hover:text-gray-600 hover:border-gray-300': activeTab !== 'filter' }"
                    class="inline-block rounded-t-lg border-b-2 p-4">
                    Điều kiện lọc
                </a>
            </li>
            <li class="mr-2">
                <a href="#" x-on:click.prevent="activeTab = 'content'"
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'content', 'border-transparent hover:text-gray-600 hover:border-gray-300': activeTab !== 'content' }"
                    class="inline-block rounded-t-lg border-b-2 p-4">
                    Kết quả
                </a>
            </li>
        </x-slot:nav>

        <x-slot:content>
            <div x-show="activeTab === 'filter'" class="space-y-3 pt-2">
                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Tiêu đề</label>
                    <div class="col-span-2 rounded-md border border-gray-200 bg-gray-50 px-3 py-1.5 text-sm text-gray-700">
                        {{ $pTieu_de }}
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Thời gian</label>
                    <div class="col-span-2">
                        @livewire('catalog::component.timer', key('sorptbk01-timer'))
                        <x-input-error for="pNgay1" class="mt-1" />
                        <x-input-error for="pNgay2" class="mt-1" />
                    </div>
                </div>

                <h4 class="border-t border-gray-200 pt-2 text-sm font-semibold text-gray-800">Chứng từ</h4>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Loại phiếu</label>
                    <div class="col-span-2">
                        <select wire:model="pMa_ct" class="w-full rounded-md border-gray-300 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Tất cả</option>
                            @foreach ($voucherTypes as $voucherType)
                                <option value="{{ $voucherType['ma_ct'] }}">
                                    {{ $voucherType['ten_ct'] }} ({{ $voucherType['ma_ct'] }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Trạng thái</label>
                    <div class="col-span-2">
                        <input class="w-full rounded-md border-gray-300 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" wire:model="pTrang_thai" placeholder="Để trống = tất cả" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Số chứng từ từ</label>
                    <div class="col-span-2">
                        <input class="w-full rounded-md border-gray-300 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" wire:model="pSoct1" placeholder="Ví dụ: HD0001" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Số chứng từ đến</label>
                    <div class="col-span-2">
                        <input class="w-full rounded-md border-gray-300 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" wire:model="pSoct2" placeholder="Để trống = hết kỳ" />
                    </div>
                </div>

                <h4 class="border-t border-gray-200 pt-2 text-sm font-semibold text-gray-800">Khách hàng</h4>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Mã khách hàng</label>
                    <div class="col-span-2">
                        <livewire:catalog::component.input-khachhang mode="khachhang" wire:model="pMa_kh" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Nhóm khách hàng</label>
                    <div class="col-span-2">
                        <input class="w-full rounded-md border-gray-300 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" wire:model="pMa_nhkh" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Phân loại KH 1</label>
                    <div class="col-span-2">
                        <input class="w-full rounded-md border-gray-300 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" wire:model="pMa_plkh1" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Phân loại KH 2</label>
                    <div class="col-span-2">
                        <input class="w-full rounded-md border-gray-300 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" wire:model="pMa_plkh2" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Phân loại KH 3</label>
                    <div class="col-span-2">
                        <input class="w-full rounded-md border-gray-300 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" wire:model="pMa_plkh3" />
                    </div>
                </div>

                <h4 class="border-t border-gray-200 pt-2 text-sm font-semibold text-gray-800">Hợp đồng</h4>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Mã hợp đồng</label>
                    <div class="col-span-2">
                        <input class="w-full rounded-md border-gray-300 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" wire:model="pMa_hd" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Nhóm hợp đồng</label>
                    <div class="col-span-2">
                        <input class="w-full rounded-md border-gray-300 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" wire:model="pMa_nhhd" />
                    </div>
                </div>

                <h4 class="border-t border-gray-200 pt-2 text-sm font-semibold text-gray-800">Vật tư</h4>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Mã vật tư</label>
                    <div class="col-span-2">
                        <livewire:catalog::component.input-indmvt wire:model="pMa_vt" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Nhóm vật tư</label>
                    <div class="col-span-2">
                        <input class="w-full rounded-md border-gray-300 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" wire:model="pMa_nhvt" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Phân loại VT 1</label>
                    <div class="col-span-2">
                        <input class="w-full rounded-md border-gray-300 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" wire:model="pMa_plvt1" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Phân loại VT 2</label>
                    <div class="col-span-2">
                        <input class="w-full rounded-md border-gray-300 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" wire:model="pMa_plvt2" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Phân loại VT 3</label>
                    <div class="col-span-2">
                        <input class="w-full rounded-md border-gray-300 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" wire:model="pMa_plvt3" />
                    </div>
                </div>

                <h4 class="border-t border-gray-200 pt-2 text-sm font-semibold text-gray-800">Kho và điều kiện khác</h4>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Kho</label>
                    <div class="col-span-2">
                        <livewire:catalog::component.input-indmkho wire:model="pMa_kho" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Vị trí</label>
                    <div class="col-span-2">
                        <input class="w-full rounded-md border-gray-300 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" wire:model="pMa_vitri" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Lô</label>
                    <div class="col-span-2">
                        <input class="w-full rounded-md border-gray-300 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" wire:model="pMa_lo" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Hình thức thanh toán</label>
                    <div class="col-span-2">
                        <input class="w-full rounded-md border-gray-300 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" wire:model="pMa_httt" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Điều khoản thanh toán</label>
                    <div class="col-span-2">
                        <input class="w-full rounded-md border-gray-300 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" wire:model="pMa_dktt" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Bộ phận</label>
                    <div class="col-span-2">
                        <input class="w-full rounded-md border-gray-300 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" wire:model="pMa_bp" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Nhân viên kinh doanh</label>
                    <div class="col-span-2">
                        <input class="w-full rounded-md border-gray-300 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" wire:model="pMa_nvkd" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Sản phẩm chi tiết</label>
                    <div class="col-span-2">
                        <input class="w-full rounded-md border-gray-300 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" wire:model="pMa_spct" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Mã ngoại tệ</label>
                    <div class="col-span-2">
                        <input class="w-full rounded-md border-gray-300 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" wire:model="pMa_nt" placeholder="Để trống = VND" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4 pt-1">
                    <span></span>
                    <div class="col-span-2 flex flex-wrap items-center gap-2">
                        <x-button-loading
                            class="rounded-md bg-blue-600 px-3 py-1.5 text-sm text-white hover:bg-blue-700"
                            wire:click="submit">
                            Thực hiện
                        </x-button-loading>
                        @if ([] !== $phieuRows)
                            <x-button-loading
                                class="rounded-md bg-gray-700 px-3 py-1.5 text-sm text-white hover:bg-gray-800"
                                wire:click="exportCsv">
                                Xuất Excel
                            </x-button-loading>
                        @endif
                    </div>
                </div>
            </div>

            <div x-show="activeTab === 'content'" class="w-full overflow-x-auto py-2">
                @if ($errorMessage)
                    <div class="mb-3 rounded border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                        {{ $errorMessage }}
                    </div>
                @endif

                @if ([] === $phieuRows)
                    <div class="rounded border border-gray-200 bg-gray-50 p-4 text-sm text-gray-600">
                        Chưa có dữ liệu. Nhập điều kiện lọc rồi bấm Thực hiện.
                    </div>
                @else
                    <div class="space-y-4">
                        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                            <div class="border-b border-gray-200 bg-gray-50 px-3 py-2 text-sm font-medium text-gray-700">
                                Danh sách phiếu
                            </div>
                            <div class="max-h-[calc(100vh-300px)] overflow-y-auto">
                                <table class="min-w-max text-left text-xs">
                                    <thead class="sticky top-0 z-10 bg-gray-50 text-gray-500">
                                        <tr>
                                            <th class="border-b border-gray-200 px-2 py-2 text-right font-medium">#</th>
                                            @foreach ($phieuColumns as $column)
                                                <th class="{{ $column['class'] }} border-b border-gray-200 px-2 py-2 font-medium">
                                                    {{ $column['label'] }}
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach ($phieuRows as $phieu)
                                            <tr wire:click="selectPhieu({{ $loop->index }})"
                                                class="cursor-pointer {{ $selectedPhieuIndex === $loop->index ? 'bg-sky-50' : 'hover:bg-sky-50' }}">
                                                <td class="px-2 py-2 text-right tabular-nums text-gray-400">{{ $loop->iteration }}</td>
                                                @foreach ($phieuColumns as $column)
                                                    <td class="{{ $column['class'] }} px-2 py-2 text-gray-700">
                                                        {{ $this->phieuCellValue($phieu, $column['key']) }}
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="flex items-center justify-between border-t border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-500">
                                <span>{{ number_format(count($phieuRows)) }} phiếu</span>
                                <x-button-loading
                                    class="rounded-md bg-gray-700 px-2.5 py-1 text-xs text-white hover:bg-gray-800"
                                    wire:click="exportCsv">
                                    Xuất Excel
                                </x-button-loading>
                            </div>
                        </div>

                        @if ([] !== $selectedPhieu)
                            <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                                <div class="flex items-center justify-between border-b border-gray-200 bg-gray-50 px-3 py-2">
                                    <span class="text-sm font-medium text-gray-700">Chi tiết phiếu #{{ ($selectedPhieuIndex ?? 0) + 1 }}</span>
                                    <span class="font-mono text-xs text-gray-500">{{ $this->phieuCellValue($selectedPhieu, 'so_ct') }}</span>
                                </div>
                                @if ([] !== $chiTietFiltered)
                                    <div class="max-h-[calc(100vh-420px)] overflow-y-auto">
                                        <table class="min-w-max text-left text-xs">
                                            <thead class="sticky top-0 z-10 bg-gray-50 text-gray-500">
                                                <tr>
                                                    <th class="border-b border-gray-200 px-2 py-2 text-right font-medium">#</th>
                                                    @foreach ($chiTietColumns as $column)
                                                        <th class="{{ $column['class'] }} border-b border-gray-200 px-2 py-2 font-medium">
                                                            {{ $column['label'] }}
                                                        </th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-100">
                                                @foreach ($chiTietFiltered as $chiTiet)
                                                    <tr class="hover:bg-sky-50">
                                                        <td class="px-2 py-2 text-right tabular-nums text-gray-400">{{ $loop->iteration }}</td>
                                                        @foreach ($chiTietColumns as $column)
                                                            <td class="{{ $column['class'] }} px-2 py-2 text-gray-700">
                                                                {{ $this->chiTietCellValue($chiTiet, $column['key']) }}
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="border-t border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-500">
                                        {{ number_format(count($chiTietFiltered)) }} dòng chi tiết
                                    </div>
                                @else
                                    <div class="p-4 text-sm text-gray-600">Phiếu không có chi tiết.</div>
                                @endif
                            </div>
                        @else
                            <div class="rounded border border-gray-200 bg-gray-50 p-4 text-sm text-gray-600">
                                Chọn một phiếu ở bảng trên để xem chi tiết.
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </x-slot:content>
    </x-nav-tabs>
</div>
