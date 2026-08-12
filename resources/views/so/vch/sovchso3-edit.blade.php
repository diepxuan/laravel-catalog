<div>
    <x-head-title>{{ ($mode === 'create' ? 'Thêm' : 'Sửa') . ' hóa đơn bán hàng (SO3)' }}</x-head-title>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ ($mode === 'create' ? 'Thêm mới' : 'Sửa') . ' hóa đơn bán hàng' }}
                </h2>
                <p>Chứng từ: SO3 — Hóa đơn bán hàng kiêm phiếu xuất kho</p>
            </div>
            <a href="{{ simbaroute('so.vch.sovchso3') }}"
                class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                ← Quay lại
            </a>
        </div>
    </x-slot>

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
    @if (session()->has('warning'))
        <div class="mb-4 rounded border border-yellow-400 bg-yellow-100 px-4 py-2 text-xs text-yellow-800">
            {{ session('warning') }}
        </div>
    @endif

    <form wire:submit.prevent="submit">
        <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="grid grid-cols-12 gap-0 border-b border-gray-200">
                <div class="col-span-8 border-r border-gray-200 p-5">
                    <h3 class="mb-4 border-b border-gray-100 pb-2 text-sm font-semibold text-gray-700">Khách hàng</h3>
                    <div class="grid grid-cols-12 items-center gap-x-4 gap-y-3">
                        <label class="col-span-3 text-right text-sm text-gray-700">Mã khách hàng <span class="text-red-500">*</span></label>
                        <div class="col-span-9">
                            <livewire:catalog::component.input-khachhang mode="khachhang" wire:model.live="pMa_kh" />
                            <x-input-error for="pMa_kh" class="mt-1" />
                        </div>

                        <label class="col-span-3 text-right text-sm text-gray-700">Tên khách hàng</label>
                        <div class="col-span-9">
                            <input wire:model="pTen_kh" readonly class="w-full rounded-md border border-gray-200 bg-gray-50 px-3 py-1.5 text-sm shadow-sm" />
                        </div>

                        <label class="col-span-3 text-right text-sm text-gray-700">Tên trên hóa đơn</label>
                        <div class="col-span-9">
                            <input wire:model="pTen_kh_vat" class="w-full rounded-md border border-gray-200 px-3 py-1.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        </div>

                        <label class="col-span-3 text-right text-sm text-gray-700">Địa chỉ xuất hóa đơn</label>
                        <div class="col-span-9">
                            <input wire:model="pDia_chi_vat" class="w-full rounded-md border border-gray-200 px-3 py-1.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        </div>

                        <label class="col-span-3 text-right text-sm text-gray-700">Mã số thuế</label>
                        <div class="col-span-9">
                            <input wire:model="pMa_so_thue" class="w-full rounded-md border border-gray-200 px-3 py-1.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        </div>

                        <label class="col-span-3 text-right text-sm text-gray-700">Người mua</label>
                        <div class="col-span-9">
                            <input wire:model="pNguoi_gd" class="w-full rounded-md border border-gray-200 px-3 py-1.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        </div>

                        <label class="col-span-3 text-right text-sm text-gray-700">Giới hạn công nợ</label>
                        <div class="col-span-9">
                            <input wire:model="pGh_no" readonly class="w-full rounded-md border border-gray-200 bg-gray-50 px-3 py-1.5 text-sm shadow-sm" />
                        </div>

                        <label class="col-span-3 text-right text-sm text-gray-700">Dư hiện thời</label>
                        <div class="col-span-9">
                            <input wire:model="pDu13" readonly class="w-full rounded-md border border-gray-200 bg-gray-50 px-3 py-1.5 text-sm shadow-sm" />
                            @if ((float) $pGh_no > 0 && (float) $pDu13 >= (float) $pGh_no)
                                <p class="mt-1 text-xs text-yellow-700">Khách hàng đã đạt hoặc vượt giới hạn công nợ.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-span-4 p-5">
                    <h3 class="mb-4 border-b border-gray-100 pb-2 text-sm font-semibold text-gray-700">Thông tin chứng từ</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-500">Ngày hóa đơn <span class="text-red-500">*</span></label>
                            <input type="date" wire:model="pNgay_ct" required class="w-full rounded-md border border-gray-200 px-3 py-1.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                            <x-input-error for="pNgay_ct" class="mt-1" />
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-500">Ngày lập CT <span class="text-red-500">*</span></label>
                            <input type="date" wire:model="pNgay_lct" required class="w-full rounded-md border border-gray-200 px-3 py-1.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                            <x-input-error for="pNgay_lct" class="mt-1" />
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-500">Số hóa đơn <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="pSo_ct" class="w-full rounded-md border border-gray-200 px-3 py-1.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                            <x-input-error for="pSo_ct" class="mt-1" />
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-500">Số seri</label>
                            <input type="text" wire:model="pSo_seri" class="w-full rounded-md border border-gray-200 px-3 py-1.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-500">Mã ngoại tệ <span class="text-red-500">*</span></label>
                            <livewire:catalog::component.input-ngoaite wire:model="pMa_nt" placeholder="Chọn ngoại tệ" />
                            <x-input-error for="pMa_nt" class="mt-1" />
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-500">Tỷ giá <span class="text-red-500">*</span></label>
                            <input type="number" step="0.0001" wire:model="pTy_gia" class="w-full rounded-md border border-gray-200 px-3 py-1.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                            <x-input-error for="pTy_gia" class="mt-1" />
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-500">Số phiếu xuất</label>
                            <input type="text" wire:model="pSo_px" class="w-full rounded-md border border-gray-200 px-3 py-1.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-0 border-b border-gray-200">
                <div class="border-r border-gray-200 p-5">
                    <div class="grid grid-cols-12 items-center gap-x-4 gap-y-3">
                        <label class="col-span-4 text-right text-sm text-gray-700">Hình thức thanh toán</label>
                        <div class="col-span-8">
                            <livewire:catalog::component.input-httt wire:model="pMa_httt" module-id="SO" placeholder="Chọn hình thức thanh toán" />
                        </div>
                        <label class="col-span-4 text-right text-sm text-gray-700">TK phải thu</label>
                        <div class="col-span-8">
                            <livewire:catalog::component.input-taikhoan wire:model="pTk_pt" />
                        </div>
                        <label class="col-span-4 text-right text-sm text-gray-700">TK thuế GTGT</label>
                        <div class="col-span-8">
                            <livewire:catalog::component.input-taikhoan wire:model="pTk_thue" />
                        </div>
                        <label class="col-span-4 text-right text-sm text-gray-700">TK chiết khấu doanh số</label>
                        <div class="col-span-8">
                            <livewire:catalog::component.input-taikhoan wire:model="pTk_ck_ds" />
                        </div>
                    </div>
                </div>
                <div class="p-5">
                    <div class="space-y-3">
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-500">Tỷ lệ CK doanh số</label>
                            <input type="number" step="0.01" wire:model="pTl_ck_ds" class="w-full rounded-md border border-gray-200 px-3 py-1.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-500">Diễn giải</label>
                            <textarea wire:model="pDien_giai" rows="4" class="w-full rounded-md border border-gray-200 px-3 py-1.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-5">
                <x-nav-tabs :default-tab="'chitiet'">
                    <x-slot:nav>
                        <li class="mr-2">
                            <a href="#" x-on:click.prevent="activeTab = 'chitiet'"
                                :class="{ 'border-blue-500 text-blue-600': activeTab === 'chitiet', 'border-transparent hover:text-gray-600 hover:border-gray-300': activeTab !== 'chitiet' }"
                                class="inline-block rounded-t-lg border-b-2 p-3 text-sm">
                                Chi tiết hàng hóa
                            </a>
                        </li>
                    </x-slot:nav>
                    <x-slot:content>
                        <div class="pt-2">
                            @include('catalog::so.vch._grid-chitiet')
                        </div>
                    </x-slot:content>
                </x-nav-tabs>

                <div class="mt-4 grid grid-cols-6 gap-4 rounded-lg border border-gray-200 bg-gray-50 p-4 text-xs">
                    <div class="text-center">
                        <span class="text-gray-500">Số lượng</span>
                        <div class="font-semibold text-gray-800">{{ number_format($pT_so_luong, 2, ',', '.') }}</div>
                    </div>
                    <div class="text-center">
                        <span class="text-gray-500">Tiền hàng NT</span>
                        <div class="font-semibold text-gray-800">{{ number_format($pT_tien_nt2, 2, ',', '.') }}</div>
                    </div>
                    <div class="text-center">
                        <span class="text-gray-500">Chiết khấu NT</span>
                        <div class="font-semibold text-gray-800">{{ number_format($pT_ck_nt + $pT_ck_ds_nt, 2, ',', '.') }}</div>
                    </div>
                    <div class="text-center">
                        <span class="text-gray-500">Thuế NT</span>
                        <div class="font-semibold text-gray-800">{{ number_format($pT_thue_nt, 2, ',', '.') }}</div>
                    </div>
                    <div class="text-center">
                        <span class="text-gray-500">Tổng VND</span>
                        <div class="font-semibold text-gray-800">{{ number_format($pT_tt, 0, ',', '.') }}</div>
                    </div>
                    <div class="text-center">
                        <span class="text-gray-500">Tổng TT NT</span>
                        <div class="text-lg font-semibold text-blue-700">{{ number_format($pT_tt_nt, 2, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 flex justify-end gap-2">
            @if ('edit' === $mode)
                <button type="button" wire:click="deleteInvoice" wire:confirm="Bạn có chắc chắn muốn xóa hóa đơn {{ $pSo_ct }}?"
                    class="inline-flex items-center rounded-md bg-red-600 px-4 py-2 text-xs font-semibold text-white hover:bg-red-500">
                    Xóa hóa đơn
                </button>
            @endif
            <a href="{{ simbaroute('so.vch.sovchso3') }}"
                class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                Hủy
            </a>
            <x-button-loading
                class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-700"
                wire:click="submit">
                Lưu hóa đơn
            </x-button-loading>
        </div>
    </form>
</div>
