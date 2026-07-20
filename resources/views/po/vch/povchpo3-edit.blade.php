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

    <form wire:submit.prevent="submit">
        <x-nav-tabs :default-tab="'chitiet'">
            <x-slot:nav>
                <li class="mr-2">
                    <a href="#" x-on:click.prevent="activeTab = 'chitiet'"
                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'chitiet', 'border-transparent hover:text-gray-600 hover:border-gray-300': activeTab !== 'chitiet' }"
                        class="inline-block rounded-t-lg border-b-2 p-4">
                        Thông tin chung + Chi tiết
                    </a>
                </li>
                <li class="mr-2">
                    <a href="#" x-on:click.prevent="activeTab = 'chiphi'"
                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'chiphi', 'border-transparent hover:text-gray-600 hover:border-gray-300': activeTab !== 'chiphi' }"
                        class="inline-block rounded-t-lg border-b-2 p-4">
                        Chi phí
                    </a>
                </li>
                <li class="mr-2">
                    <a href="#" x-on:click.prevent="activeTab = 'hachtoan'"
                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'hachtoan', 'border-transparent hover:text-gray-600 hover:border-gray-300': activeTab !== 'hachtoan' }"
                        class="inline-block rounded-t-lg border-b-2 p-4">
                        Hạch toán (deferred)
                    </a>
                </li>
            </x-slot:nav>

            <x-slot:content>
                <div x-show="activeTab === 'chitiet'" class="space-y-3 pt-2">
                    <h3 class="text-sm font-semibold text-gray-700 border-b pb-2">Thông tin chung</h3>
                    <div class="grid grid-cols-3 items-center gap-4">
                        <label class="text-right text-sm text-gray-700">Ngày chứng từ <span class="text-red-500">*</span></label>
                        <div class="col-span-2">
                            <input type="date" wire:model="pNgay_ct" required
                                class="w-full rounded-md border border-gray-200 px-3 py-1.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                            <x-input-error for="pNgay_ct" class="mt-1" />
                        </div>
                    </div>
                    <div class="grid grid-cols-3 items-center gap-4">
                        <label class="text-right text-sm text-gray-700">Ngày lập CT</label>
                        <div class="col-span-2">
                            <input type="date" wire:model="pNgay_lct"
                                class="w-full rounded-md border border-gray-200 px-3 py-1.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                            <x-input-error for="pNgay_lct" class="mt-1" />
                        </div>
                    </div>
                    <div class="grid grid-cols-3 items-center gap-4">
                        <label class="text-right text-sm text-gray-700">Số chứng từ</label>
                        <div class="col-span-2">
                            <input type="text" wire:model="pSo_ct"
                                class="w-full rounded-md border border-gray-200 bg-gray-50 px-3 py-1.5 text-sm shadow-sm" />
                        </div>
                    </div>
                    <div class="grid grid-cols-3 items-center gap-4">
                        <label class="text-right text-sm text-gray-700">Mã ngoại tệ</label>
                        <div class="col-span-2">
                            <input wire:model="pMa_nt"
                                class="w-full rounded-md border border-gray-200 px-3 py-1.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="VND" />
                            <x-input-error for="pMa_nt" class="mt-1" />
                        </div>
                    </div>
                    <div class="grid grid-cols-3 items-center gap-4">
                        <label class="text-right text-sm text-gray-700">Tỷ giá</label>
                        <div class="col-span-2">
                            <input type="number" step="0.0001" wire:model="pTy_gia"
                                class="w-full rounded-md border border-gray-200 px-3 py-1.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        </div>
                    </div>
                    <div class="grid grid-cols-3 items-center gap-4">
                        <label class="text-right text-sm text-gray-700">Số hóa đơn <span class="text-red-500">*</span></label>
                        <div class="col-span-2">
                            <input type="text" wire:model="pSo_hd" required
                                class="w-full rounded-md border border-gray-200 px-3 py-1.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                            <x-input-error for="pSo_hd" class="mt-1" />
                        </div>
                    </div>
                    <div class="grid grid-cols-3 items-center gap-4">
                        <label class="text-right text-sm text-gray-700">Ngày hóa đơn <span class="text-red-500">*</span></label>
                        <div class="col-span-2">
                            <input type="date" wire:model="pNgay_hd" required
                                class="w-full rounded-md border border-gray-200 px-3 py-1.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                            <x-input-error for="pNgay_hd" class="mt-1" />
                        </div>
                    </div>

                    <h3 class="text-sm font-semibold text-gray-700 border-b pb-2 pt-4">Nhà cung cấp</h3>
                    <div class="grid grid-cols-3 items-center gap-4">
                        <label class="text-right text-sm text-gray-700">Mã NCC <span class="text-red-500">*</span></label>
                        <div class="col-span-2">
                            <livewire:catalog::component.input-khachhang mode="nhacungcap" wire:model.live="pMa_kh" />
                            <x-input-error for="pMa_kh" class="mt-1" />
                        </div>
                    </div>
                    <div class="grid grid-cols-3 items-center gap-4">
                        <label class="text-right text-sm text-gray-700">Tên NCC</label>
                        <div class="col-span-2">
                            <input wire:model="pTen_kh"
                                class="w-full rounded-md border border-gray-200 bg-gray-50 px-3 py-1.5 text-sm shadow-sm" readonly />
                        </div>
                    </div>
                    <div class="grid grid-cols-3 items-center gap-4">
                        <label class="text-right text-sm text-gray-700">Địa chỉ</label>
                        <div class="col-span-2">
                            <input wire:model="pDia_chi"
                                class="w-full rounded-md border border-gray-200 bg-gray-50 px-3 py-1.5 text-sm shadow-sm" readonly />
                        </div>
                    </div>
                    <div class="grid grid-cols-3 items-center gap-4">
                        <label class="text-right text-sm text-gray-700">Mã số thuế</label>
                        <div class="col-span-2">
                            <input wire:model="pMa_so_thue"
                                class="w-full rounded-md border border-gray-200 bg-gray-50 px-3 py-1.5 text-sm shadow-sm" readonly />
                        </div>
                    </div>
                    <div class="grid grid-cols-3 items-center gap-4">
                        <label class="text-right text-sm text-gray-700">Người giao dịch</label>
                        <div class="col-span-2">
                            <input wire:model="pNguoi_gd"
                                class="w-full rounded-md border border-gray-200 bg-gray-50 px-3 py-1.5 text-sm shadow-sm" readonly />
                        </div>
                    </div>

                    <div class="grid grid-cols-3 items-center gap-4">
                        <label class="text-right text-sm text-gray-700">Hình thức TT</label>
                        <div class="col-span-2">
                            <livewire:catalog::component.input-httt :value="$pMa_httt" module-id="PO" placeholder="Chọn hình thức thanh toán" />
                            <x-input-error for="pMa_httt" class="mt-1" />
                        </div>
                    </div>
                    <div class="grid grid-cols-3 items-center gap-4">
                        <label class="text-right text-sm text-gray-700">TK phải trả</label>
                        <div class="col-span-2">
                            <livewire:catalog::component.input-taikhoan wire:model="pTk_pt" />
                            <x-input-error for="pTk_pt" class="mt-1" />
                        </div>
                    </div>
                    <div class="grid grid-cols-3 items-center gap-4">
                        <label class="text-right text-sm text-gray-700">TK thuế</label>
                        <div class="col-span-2">
                            <livewire:catalog::component.input-taikhoan wire:model="pTk_thue" />
                            <x-input-error for="pTk_thue" class="mt-1" />
                        </div>
                    </div>
                    <div class="grid grid-cols-3 items-center gap-4">
                        <label class="text-right text-sm text-gray-700">Diễn giải</label>
                        <div class="col-span-2">
                            <textarea wire:model="pDien_giai" rows="2"
                                class="w-full rounded-md border border-gray-200 px-3 py-1.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                        </div>
                    </div>

                    <h3 class="text-sm font-semibold text-gray-700 border-b pb-2 pt-4">Chi tiết vật tư</h3>
                    @include('catalog::po.vch._grid-chitiet')
                </div>

                <div x-show="activeTab === 'chiphi'" class="space-y-3 pt-2">
                    <h3 class="text-sm font-semibold text-gray-700 border-b pb-2">Chi phí mua hàng</h3>
                    @include('catalog::po.vch._grid-chiphi')
                </div>

                <div x-show="activeTab === 'hachtoan'" class="space-y-3 pt-2">
                    <h3 class="text-sm font-semibold text-gray-700 border-b pb-2">Hạch toán (deferred)</h3>
                    <p class="text-sm text-gray-600">Tab hạch toán sẽ được bổ sung ở phase tiếp theo. Tham khảo POVchPO3.md section 6.3 để xác định auto-gen logic.</p>
                </div>
            </x-slot:content>
        </x-nav-tabs>

        <div class="mt-4 flex justify-end gap-2 border-t border-gray-200 pt-3">
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
