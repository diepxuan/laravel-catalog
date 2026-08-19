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
                        @if ($hasData)
                            <x-button-loading
                                class="rounded-md bg-gray-700 px-3 py-1.5 text-sm text-white hover:bg-gray-800"
                                wire:click="exportCsv">
                                Xuất Excel
                            </x-button-loading>
                        @endif
                    </div>
                </div>
            </div>

            <div x-show="activeTab === 'content'"
                x-data="sorptbk01Report(@js($editUrlTemplate))"
                @sorptbk01-report-loaded.window="onLoaded($event.detail)"
                @sorptbk01-voucher-deleted.window="onDeleted($event.detail)"
                class="w-full overflow-x-auto py-2">
                @if ($errorMessage)
                    <div class="mb-3 rounded border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                        {{ $errorMessage }}
                    </div>
                @endif

                <div x-show="!loaded" class="rounded border border-gray-200 bg-gray-50 p-4 text-sm text-gray-600">
                    Chưa có dữ liệu. Nhập điều kiện lọc rồi bấm Thực hiện.
                </div>

                <div x-show="loaded" class="space-y-4">
                    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                        <div class="border-b border-gray-200 bg-gray-50 px-3 py-2 text-sm font-medium text-gray-700">
                            Danh sách phiếu
                        </div>
                        <div class="max-h-[calc(100vh-300px)] overflow-y-auto">
                            <table class="min-w-max text-left text-xs">
                                <thead class="sticky top-0 z-10 bg-gray-50 text-gray-500">
                                    <tr>
                                        <th class="border-b border-gray-200 px-2 py-2 text-right font-medium">#</th>
                                        <template x-for="column in phieuColumns" :key="column.key">
                                            <th :class="column.class" class="border-b border-gray-200 px-2 py-2 font-medium" x-text="column.label"></th>
                                        </template>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <template x-for="(phieu, index) in phieuRows" :key="phieu.stt_rec">
                                        <tr @click="selectPhieu(index)"
                                            :class="selectedIndex === index ? 'bg-sky-50' : 'cursor-pointer hover:bg-sky-50'">
                                            <td class="px-2 py-2 text-right tabular-nums text-gray-400" x-text="index + 1"></td>
                                            <template x-for="(cell, ci) in phieu.cells" :key="ci">
                                                <td :class="[phieuColumns[ci].class, cell.c]" class="px-2 py-2" x-text="cell.v"></td>
                                            </template>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-500">
                            <span x-text="phieuRows.length.toLocaleString('vi-VN') + ' phiếu'"></span>
                            <x-button-loading
                                class="rounded-md bg-gray-700 px-2.5 py-1 text-xs text-white hover:bg-gray-800"
                                wire:click="exportCsv">
                                Xuất Excel
                            </x-button-loading>
                        </div>
                    </div>

                    <template x-if="selectedPhieu">
                        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                            <div class="flex items-center justify-between border-b border-gray-200 bg-gray-50 px-3 py-2">
                                <span class="text-sm font-medium text-gray-700" x-text="'Chi tiết phiếu #' + ((selectedIndex ?? 0) + 1)"></span>
                                <span class="font-mono text-xs text-gray-500" x-text="selectedPhieu.so_ct"></span>
                            </div>
                            <template x-if="chiTietFiltered.length > 0">
                                <div>
                                    <div class="max-h-[calc(100vh-420px)] overflow-y-auto">
                                        <table class="min-w-max text-left text-xs">
                                            <thead class="sticky top-0 z-10 bg-gray-50 text-gray-500">
                                                <tr>
                                                    <th class="border-b border-gray-200 px-2 py-2 text-right font-medium">#</th>
                                                    <template x-for="column in chiTietColumns" :key="column.key">
                                                        <th :class="column.class" class="border-b border-gray-200 px-2 py-2 font-medium" x-text="column.label"></th>
                                                    </template>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-100">
                                                <template x-for="(chiTiet, index) in chiTietFiltered" :key="chiTiet.stt_rec + '-' + index">
                                                    <tr class="hover:bg-sky-50">
                                                        <td class="px-2 py-2 text-right tabular-nums text-gray-400" x-text="index + 1"></td>
                                                        <template x-for="(cell, ci) in chiTiet.cells" :key="ci">
                                                            <td :class="[chiTietColumns[ci].class, cell.c]" class="px-2 py-2" x-text="cell.v"></td>
                                                        </template>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="flex items-center justify-between border-t border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-500">
                                        <span x-text="chiTietFiltered.length.toLocaleString('vi-VN') + ' dòng chi tiết'"></span>
                                        <template x-if="selectedPhieu.ma_ct === 'SO3' && selectedPhieu.stt_rec">
                                            <div class="flex items-center gap-2">
                                                <a :href="editUrlFor(selectedPhieu)"
                                                    class="rounded bg-yellow-100 px-2 py-1 text-xs text-yellow-700 hover:bg-yellow-200">
                                                    Sửa
                                                </a>
                                                <button type="button"
                                                    @click="confirmDelete(selectedPhieu)"
                                                    class="rounded bg-red-100 px-2 py-1 text-xs text-red-700 hover:bg-red-200">
                                                    Xóa
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                            <template x-if="chiTietFiltered.length === 0">
                                <div class="p-4 text-sm text-gray-600">Phiếu không có chi tiết.</div>
                            </template>
                        </div>
                    </template>

                    <template x-if="!selectedPhieu">
                        <div class="rounded border border-gray-200 bg-gray-50 p-4 text-sm text-gray-600">
                            Chọn một phiếu ở bảng trên để xem chi tiết.
                        </div>
                    </template>
                </div>
            </div>
        </x-slot:content>
    </x-nav-tabs>
</div>

@once
@push('scripts')
<script>
    (function () {
        if (typeof window.sorptbk01Report !== 'undefined') {
            return;
        }

        window.sorptbk01Report = function (editUrlTemplate) {
            return {
                loaded: false,
                phieuColumns: [],
                chiTietColumns: [],
                phieuRows: [],
                chiTietRows: [],
                selectedIndex: null,
                selectedPhieu: null,
                chiTietFiltered: [],
                editUrlTemplate: editUrlTemplate || '#',

                onLoaded(detail) {
                    this.phieuColumns = detail.phieuColumns || [];
                    this.chiTietColumns = detail.chiTietColumns || [];
                    this.phieuRows = detail.phieu || [];
                    this.chiTietRows = detail.chiTiet || [];
                    this.loaded = this.phieuRows.length > 0;
                    this.selectPhieu(this.loaded ? 0 : null);
                },

                onDeleted(detail) {
                    const sttRec = detail.sttRec;
                    this.phieuRows = this.phieuRows.filter((row) => row.stt_rec !== sttRec);
                    this.chiTietRows = this.chiTietRows.filter((row) => row.stt_rec !== sttRec);
                    this.loaded = this.phieuRows.length > 0;
                    this.selectPhieu(this.loaded ? 0 : null);
                },

                selectPhieu(index) {
                    if (index === null || !this.phieuRows[index]) {
                        this.selectedIndex = null;
                        this.selectedPhieu = null;
                        this.chiTietFiltered = [];

                        return;
                    }

                    this.selectedIndex = index;
                    this.selectedPhieu = this.phieuRows[index];
                    const sttRec = this.selectedPhieu.stt_rec;
                    this.chiTietFiltered = this.chiTietRows.filter((row) => row.stt_rec === sttRec);
                },

                editUrlFor(phieu) {
                    if (!phieu || !phieu.stt_rec) {
                        return '#';
                    }

                    return this.editUrlTemplate.replace('__STT_REC__', encodeURIComponent(phieu.stt_rec));
                },

                confirmDelete(phieu) {
                    if (!confirm('Bạn có chắc chắn muốn xóa chứng từ ' + phieu.so_ct + '?')) {
                        return;
                    }

                    this.$wire.deleteVoucher(phieu.stt_rec);
                },
            };
        };
    })();
</script>
@endpush
@endonce
