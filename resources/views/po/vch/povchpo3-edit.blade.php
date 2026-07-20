<div>
    <x-head-title>{{ ($mode === 'create' ? 'Thêm' : 'Sửa') . ' hóa đơn mua hàng (PO3)' }}</x-head-title>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ ($mode === 'create' ? 'Thêm mới' : 'Sửa') . ' hóa đơn mua hàng' }}
                </h2>
                <p>Chứng từ: PO3 — Hóa đơn mua hàng trong nước</p>
            </div>
            <a href="{{ route('po.vch.povchpo3') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500">
                ← Quay lại
            </a>
        </div>
    </x-slot>

    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4 text-xs">
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4 text-xs">
            {{ session('error') }}
        </div>
    @endif

    @if (session()->has('success'))
        <div class="mb-4 rounded border border-green-400 bg-green-100 px-4 py-2 text-xs text-green-700">
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-4 rounded border border-red-400 bg-red-100 px-4 py-2 text-xs text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <form wire:submit.prevent="submit">
        <!-- Two-column header: NCC (left 2/3) + Chung (right 1/3) -->
        <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="grid grid-cols-12 gap-0 border-b border-gray-200">
                <!-- LEFT: Nha cung cap (2/3) -->
                <div class="col-span-8 border-r border-gray-200 p-5">
                    <h3 class="mb-4 text-sm font-semibold text-gray-700 border-b border-gray-100 pb-2">Nhà cung cấp</h3>
                    <div class="grid grid-cols-12 items-center gap-x-4 gap-y-3">
                        <label class="col-span-3 text-right text-sm text-gray-700">Mã NCC <span class="text-red-500">*</span></label>
                        <div class="col-span-9">
                            <livewire:catalog::component.input-khachhang mode="nhacungcap" wire:model.live="pMa_kh" />
                            <x-input-error for="pMa_kh" class="mt-1" />
                        </div>

                        <label class="col-span-3 text-right text-sm text-gray-700">Tên NCC</label>
                        <div class="col-span-9">
                            <input wire:model="pTen_kh" readonly
                                class="w-full rounded-md border border-gray-200 bg-gray-50 px-3 py-1.5 text-sm shadow-sm" />
                        </div>

                        <label class="col-span-3 text-right text-sm text-gray-700">Địa chỉ</label>
                        <div class="col-span-9">
                            <input wire:model="pDia_chi" readonly
                                class="w-full rounded-md border border-gray-200 bg-gray-50 px-3 py-1.5 text-sm shadow-sm" />
                        </div>

                        <label class="col-span-3 text-right text-sm text-gray-700">Mã số thuế</label>
                        <div class="col-span-9">
                            <input wire:model="pMa_so_thue" readonly
                                class="w-full rounded-md border border-gray-200 bg-gray-50 px-3 py-1.5 text-sm shadow-sm" />
                        </div>

                        <label class="col-span-3 text-right text-sm text-gray-700">Người giao dịch</label>
                        <div class="col-span-9">
                            <input wire:model="pNguoi_gd" readonly
                                class="w-full rounded-md border border-gray-200 bg-gray-50 px-3 py-1.5 text-sm shadow-sm" />
                        </div>

                        <label class="col-span-3 text-right text-sm text-gray-700">Hình thức TT</label>
                        <div class="col-span-9">
                            <livewire:catalog::component.input-httt :value="$pMa_httt" module-id="PO" placeholder="Chọn hình thức thanh toán" />
                            <x-input-error for="pMa_httt" class="mt-1" />
                        </div>

                        <label class="col-span-3 text-right text-sm text-gray-700">TK phải trả</label>
                        <div class="col-span-9">
                            <livewire:catalog::component.input-taikhoan wire:model="pTk_pt" />
                            <x-input-error for="pTk_pt" class="mt-1" />
                        </div>

                        <label class="col-span-3 text-right text-sm text-gray-700">TK thuế</label>
                        <div class="col-span-9">
                            <livewire:catalog::component.input-taikhoan wire:model="pTk_thue" />
                            <x-input-error for="pTk_thue" class="mt-1" />
                        </div>
                    </div>
                </div>

                <!-- RIGHT: Thong tin chung (1/3) -->
                <div class="col-span-4 p-5">
                    <h3 class="mb-4 text-sm font-semibold text-gray-700 border-b border-gray-100 pb-2">Thông tin chứng từ</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-500">Ngày chứng từ <span class="text-red-500">*</span></label>
                            <input type="date" wire:model="pNgay_ct" required
                                class="w-full rounded-md border border-gray-200 px-3 py-1.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                            <x-input-error for="pNgay_ct" class="mt-1" />
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-500">Ngày lập CT</label>
                            <input type="date" wire:model="pNgay_lct"
                                class="w-full rounded-md border border-gray-200 px-3 py-1.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                            <x-input-error for="pNgay_lct" class="mt-1" />
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-500">Số chứng từ</label>
                            <input type="text" wire:model="pSo_ct"
                                class="w-full rounded-md border border-gray-200 bg-gray-50 px-3 py-1.5 text-sm shadow-sm" />
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-500">Số hóa đơn</label>
                            <input type="text" wire:model="pSo_hd"
                                class="w-full rounded-md border border-gray-200 px-3 py-1.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                            <x-input-error for="pSo_hd" class="mt-1" />
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-500">Ngày hóa đơn <span class="text-red-500">*</span></label>
                            <input type="date" wire:model="pNgay_hd" required
                                class="w-full rounded-md border border-gray-200 px-3 py-1.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                            <x-input-error for="pNgay_hd" class="mt-1" />
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-500">Mã ngoại tệ</label>
                            <livewire:catalog::component.input-ngoaite wire:model="pMa_nt" placeholder="Chọn ngoại tệ" />
                            <x-input-error for="pMa_nt" class="mt-1" />
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-500">Tỷ giá</label>
                            <input type="number" step="0.0001" wire:model="pTy_gia"
                                class="w-full rounded-md border border-gray-200 px-3 py-1.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Full width: Dien giai -->
            <div class="border-b border-gray-200 p-5">
                <label class="mb-1 block text-xs font-medium text-gray-500">Diễn giải</label>
                <textarea wire:model="pDien_giai" rows="2"
                    class="w-full rounded-md border border-gray-200 px-3 py-1.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
            </div>

            <!-- Full width: Tabbed grids -->
            <div class="p-5">
                <x-nav-tabs :default-tab="'chitiet'">
                    <x-slot:nav>
                        <li class="mr-2">
                            <a href="#" x-on:click.prevent="activeTab = 'chitiet'"
                                :class="{ 'border-blue-500 text-blue-600': activeTab === 'chitiet', 'border-transparent hover:text-gray-600 hover:border-gray-300': activeTab !== 'chitiet' }"
                                class="inline-block rounded-t-lg border-b-2 p-3 text-sm">
                                Chi tiết vật tư
                            </a>
                        </li>
                        <li class="mr-2">
                            <a href="#" x-on:click.prevent="activeTab = 'chiphi'"
                                :class="{ 'border-blue-500 text-blue-600': activeTab === 'chiphi', 'border-transparent hover:text-gray-600 hover:border-gray-300': activeTab !== 'chiphi' }"
                                class="inline-block rounded-t-lg border-b-2 p-3 text-sm">
                                Chi phí
                            </a>
                        </li>
                        <li class="mr-2">
                            <a href="#" x-on:click.prevent="activeTab = 'hachtoan'"
                                :class="{ 'border-blue-500 text-blue-600': activeTab === 'hachtoan', 'border-transparent hover:text-gray-600 hover:border-gray-300': activeTab !== 'hachtoan' }"
                                class="inline-block rounded-t-lg border-b-2 p-3 text-sm">
                                Hạch toán
                            </a>
                        </li>
                    </x-slot:nav>

                    <x-slot:content>
                        <div class="pt-2">
                            <div x-show="activeTab === 'chitiet'">
                                @include('catalog::po.vch._grid-chitiet')
                            </div>
                            <div x-show="activeTab === 'chiphi'">
                                @include('catalog::po.vch._grid-chiphi')
                            </div>
                            <div x-show="activeTab === 'hachtoan'" class="p-4 text-center text-xs text-gray-500">
                                Tab hạch toán sẽ được bổ sung ở phase tiếp theo.
                            </div>
                        </div>
                    </x-slot:content>
                </x-nav-tabs>

                <!-- Totals -->
                <div class="mt-4 grid grid-cols-6 gap-4 rounded-lg border border-gray-200 bg-gray-50 p-4 text-xs">
                    <div class="text-center">
                        <span class="text-gray-500">Tổng tiền NT</span>
                        <div class="font-semibold text-gray-800">{{ number_format($pT_tien_nt0, 2, ',', '.') }}</div>
                    </div>
                    <div class="text-center">
                        <span class="text-gray-500">Tổng tiền VND</span>
                        <div class="font-semibold text-gray-800">{{ number_format($pT_tien0, 0, ',', '.') }}</div>
                    </div>
                    <div class="text-center">
                        <span class="text-gray-500">Thuế NT</span>
                        <div class="font-semibold text-gray-800">{{ number_format($pT_thue_nt, 2, ',', '.') }}</div>
                    </div>
                    <div class="text-center">
                        <span class="text-gray-500">CP NT</span>
                        <div class="font-semibold text-gray-800">{{ number_format($pT_cp_nt, 2, ',', '.') }}</div>
                    </div>
                    <div class="text-center">
                        <span class="text-gray-500">CK NT</span>
                        <div class="font-semibold text-gray-800">{{ number_format($pT_ck_nt, 2, ',', '.') }}</div>
                    </div>
                    <div class="text-center">
                        <span class="text-gray-500">Tổng TT NT</span>
                        <div class="text-lg font-bold text-blue-700">{{ number_format($pT_tt_nt, 2, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 flex justify-end gap-2">
            <a href="{{ route('po.vch.povchpo3') }}"
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                Hủy
            </a>
            <x-button-loading
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700"
                wire:click="submit">
                Lưu hóa đơn
            </x-button-loading>
        </div>
    </form>
</div>
