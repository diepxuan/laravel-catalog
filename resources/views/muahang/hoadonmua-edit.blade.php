<div class="hoadonmua-edit-container w-full">
    <x-head-title>{{ ($mode === 'create' ? 'Thêm' : 'Sửa') . ' hóa đơn mua hàng - Mua hàng' }}</x-head-title>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ ($mode === 'create' ? 'Thêm mới' : 'Sửa') . ' hóa đơn mua hàng' }}
                </h2>
                <p>{{ 'Chứng từ: PO3' }}</p>
            </div>
            <a href="{{ route('muahang.hoadonmua') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500">
                ← Quay lại
            </a>
        </div>
    </x-slot>

    {{-- Flash messages --}}
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
        {{-- HEADER --}}
        <div class="bg-white rounded-lg shadow p-4 mb-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-3 border-b pb-2">Thông tin chung</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Ngày chứng từ <span class="text-red-500">*</span></label>
                    <input type="date" wire:model="pNgay_ct" required
                        class="w-full border-gray-300 rounded-md shadow-sm text-xs focus:border-blue-500 focus:ring-blue-500" />
                    @error('pNgay_ct') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Số chứng từ</label>
                    <input type="text" wire:model="pSo_ct"
                        class="w-full border-gray-300 rounded-md shadow-sm text-xs focus:border-blue-500 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Mã ngoại tệ</label>
                    <select wire:model.live="pMa_nt"
                        class="w-full border-gray-300 rounded-md shadow-sm text-xs focus:border-blue-500 focus:ring-blue-500">
                        <option value="VND">VND</option>
                        <option value="USD">USD</option>
                        <option value="EUR">EUR</option>
                        <option value="JPY">JPY</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Số hóa đơn <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="pSo_hd" required
                        class="w-full border-gray-300 rounded-md shadow-sm text-xs focus:border-blue-500 focus:ring-blue-500" />
                    @error('pSo_hd') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Ngày hóa đơn <span class="text-red-500">*</span></label>
                    <input type="date" wire:model="pNgay_hd" required
                        class="w-full border-gray-300 rounded-md shadow-sm text-xs focus:border-blue-500 focus:ring-blue-500" />
                    @error('pNgay_hd') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tỷ giá</label>
                    <input type="number" step="0.01" wire:model.live="pTy_gia"
                        class="w-full border-gray-300 rounded-md shadow-sm text-xs focus:border-blue-500 focus:ring-blue-500" />
                </div>
            </div>

            {{-- NCC Info --}}
            <h3 class="text-sm font-semibold text-gray-700 mb-3 mt-4 border-b pb-2">Nhà cung cấp</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Mã NCC <span class="text-red-500">*</span></label>
                    <livewire:catalog::component.input-khachhang mode="nhacungcap" wire:model.live="pMa_kh"
                        placeholder="Chọn nhà cung cấp..." />
                    @error('pMa_kh') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tên NCC</label>
                    <input type="text" wire:model="pTen_kh" readonly
                        class="w-full border-gray-300 rounded-md shadow-sm text-xs bg-gray-50" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Mã số thuế</label>
                    <input type="text" wire:model="pMa_so_thue"
                        class="w-full border-gray-300 rounded-md shadow-sm text-xs focus:border-blue-500 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Người giao dịch</label>
                    <input type="text" wire:model="pNguoi_gd"
                        class="w-full border-gray-300 rounded-md shadow-sm text-xs focus:border-blue-500 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Địa chỉ</label>
                    <input type="text" wire:model="pDia_chi"
                        class="w-full border-gray-300 rounded-md shadow-sm text-xs focus:border-blue-500 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Hình thức TT</label>
                    <input type="text" wire:model="pMa_httt"
                        class="w-full border-gray-300 rounded-md shadow-sm text-xs focus:border-blue-500 focus:ring-blue-500" />
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-xs font-medium text-gray-700 mb-1">Diễn giải</label>
                <textarea wire:model="pDien_giai" rows="2"
                    class="w-full border-gray-300 rounded-md shadow-sm text-xs focus:border-blue-500 focus:ring-blue-500"></textarea>
            </div>
        </div>

        {{-- TABS --}}
        <div class="bg-white rounded-lg shadow mb-4">
            {{-- Tab headers --}}
            <div class="flex border-b">
                <button type="button" wire:click="$set('pActiveTab', 0)"
                    class="px-4 py-2 text-xs font-medium {{ $pActiveTab === 0 ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                    Chi tiết vật tư
                </button>
                <button type="button" wire:click="$set('pActiveTab', 1)"
                    class="px-4 py-2 text-xs font-medium {{ $pActiveTab === 1 ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                    Chi phí
                </button>
                <button type="button" wire:click="$set('pActiveTab', 2)"
                    class="px-4 py-2 text-xs font-medium {{ $pActiveTab === 2 ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                    Hạch toán
                </button>
            </div>

            {{-- Tab 0: Chi tiết vật tư --}}
            @if ($pActiveTab === 0)
                <div class="p-4">
                    <div class="flex justify-end mb-2">
                        <button type="button" wire:click="addChiTietRow"
                            class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-500">
                            + Thêm dòng
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-1 py-1 text-left">Mã VT</th>
                                    <th class="px-1 py-1 text-left">Tên VT</th>
                                    <th class="px-1 py-1 text-center">ĐVT</th>
                                    <th class="px-1 py-1 text-right">SL</th>
                                    <th class="px-1 py-1 text-right">Giá NT</th>
                                    <th class="px-1 py-1 text-right">Tiền NT</th>
                                    <th class="px-1 py-1 text-center">Thuế %</th>
                                    <th class="px-1 py-1 text-right">Thuế NT</th>
                                    <th class="px-1 py-1 text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pChiTiet as $i => $row)
                                    <tr class="border-b" wire:key="ct-{{ $i }}">
                                        <td class="px-1 py-1">
                                            <input type="text" wire:model.live="pChiTiet.{{ $i }}.ma_vt"
                                                class="w-20 border-gray-300 rounded text-xs" />
                                        </td>
                                        <td class="px-1 py-1">
                                            <input type="text" wire:model="pChiTiet.{{ $i }}.ten_vt"
                                                class="w-32 border-gray-300 rounded text-xs bg-gray-50" readonly />
                                        </td>
                                        <td class="px-1 py-1 text-center">
                                            <input type="text" wire:model="pChiTiet.{{ $i }}.dvt"
                                                class="w-10 border-gray-300 rounded text-xs bg-gray-50 text-center" readonly />
                                        </td>
                                        <td class="px-1 py-1">
                                            <input type="number" step="0.01" wire:model.live="pChiTiet.{{ $i }}.so_luong"
                                                wire:change="calculateChiTietRow({{ $i }})"
                                                class="w-16 border-gray-300 rounded text-xs text-right" />
                                        </td>
                                        <td class="px-1 py-1">
                                            <input type="number" step="0.01" wire:model.live="pChiTiet.{{ $i }}.gia_nt0"
                                                wire:change="calculateChiTietRow({{ $i }})"
                                                class="w-20 border-gray-300 rounded text-xs text-right" />
                                        </td>
                                        <td class="px-1 py-1 text-right font-mono">
                                            {{ number_format($row['tien_nt0'] ?? 0, 0, ',', '.') }}
                                        </td>
                                        <td class="px-1 py-1">
                                            <input type="number" step="0.01" wire:model.live="pChiTiet.{{ $i }}.ts_gtgt"
                                                wire:change="calculateChiTietRow({{ $i }})"
                                                class="w-12 border-gray-300 rounded text-xs text-center" />
                                        </td>
                                        <td class="px-1 py-1 text-right font-mono">
                                            {{ number_format($row['thue_gtgt_nt'] ?? 0, 0, ',', '.') }}
                                        </td>
                                        <td class="px-1 py-1 text-center">
                                            <button type="button" wire:click="removeChiTietRow({{ $i }})"
                                                class="text-red-500 hover:text-red-700">×</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- Tab 1: Chi phí --}}
            @if ($pActiveTab === 1)
                <div class="p-4">
                    <div class="flex justify-end mb-2">
                        <button type="button" wire:click="addChiPhiRow"
                            class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-500">
                            + Thêm dòng
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-1 py-1 text-left">Mã CP</th>
                                    <th class="px-1 py-1 text-left">Tên CP</th>
                                    <th class="px-1 py-1 text-right">Tiền CP NT</th>
                                    <th class="px-1 py-1 text-right">Tiền CP</th>
                                    <th class="px-1 py-1 text-center">Thuế %</th>
                                    <th class="px-1 py-1 text-right">Thuế NT</th>
                                    <th class="px-1 py-1 text-right">Tổng NT</th>
                                    <th class="px-1 py-1 text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pChiPhi as $i => $row)
                                    <tr class="border-b" wire:key="cp-{{ $i }}">
                                        <td class="px-1 py-1">
                                            <input type="text" wire:model="pChiPhi.{{ $i }}.ma_cp"
                                                class="w-20 border-gray-300 rounded text-xs" />
                                        </td>
                                        <td class="px-1 py-1">
                                            <input type="text" wire:model="pChiPhi.{{ $i }}.ten_cp"
                                                class="w-32 border-gray-300 rounded text-xs" />
                                        </td>
                                        <td class="px-1 py-1">
                                            <input type="number" step="0.01" wire:model.live="pChiPhi.{{ $i }}.tien_cp_nt"
                                                wire:change="calculateChiPhiRow({{ $i }})"
                                                class="w-24 border-gray-300 rounded text-xs text-right" />
                                        </td>
                                        <td class="px-1 py-1">
                                            <input type="number" step="0.01" wire:model.live="pChiPhi.{{ $i }}.tien_cp"
                                                wire:change="calculateChiPhiRow({{ $i }})"
                                                class="w-24 border-gray-300 rounded text-xs text-right" />
                                        </td>
                                        <td class="px-1 py-1">
                                            <input type="number" step="0.01" wire:model.live="pChiPhi.{{ $i }}.ts_gtgt"
                                                wire:change="calculateChiPhiRow({{ $i }})"
                                                class="w-12 border-gray-300 rounded text-xs text-center" />
                                        </td>
                                        <td class="px-1 py-1 text-right font-mono">
                                            {{ number_format($row['thue_gtgt_nt'] ?? 0, 0, ',', '.') }}
                                        </td>
                                        <td class="px-1 py-1 text-right font-mono">
                                            {{ number_format($row['tt_nt'] ?? 0, 0, ',', '.') }}
                                        </td>
                                        <td class="px-1 py-1 text-center">
                                            <button type="button" wire:click="removeChiPhiRow({{ $i }})"
                                                class="text-red-500 hover:text-red-700">×</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- Tab 2: Hạch toán --}}
            @if ($pActiveTab === 2)
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">TK Phải trả</label>
                            <input type="text" wire:model="pTk_pt"
                                class="w-full border-gray-300 rounded-md shadow-sm text-xs font-mono focus:border-blue-500 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">TK Thuế</label>
                            <input type="text" wire:model="pTk_thue"
                                class="w-full border-gray-300 rounded-md shadow-sm text-xs font-mono focus:border-blue-500 focus:ring-blue-500" />
                        </div>
                    </div>

                    {{-- Hạch toán summary --}}
                    <div class="mt-4 bg-gray-50 rounded p-3 text-xs font-mono">
                        <h4 class="font-semibold mb-2">Bút toán dự kiến:</h4>
                        <p>Nợ TK Chi phí / Vật tư: <strong>{{ number_format($pT_tien0, 0, ',', '.') }}</strong></p>
                        <p>Nợ TK Thuế GTGT: <strong>{{ number_format($pT_thue, 0, ',', '.') }}</strong></p>
                        <p class="border-t pt-1 mt-1">Có TK Phải trả NCC: <strong>{{ number_format($pT_tt, 0, ',', '.') }}</strong></p>
                    </div>
                </div>
            @endif
        </div>

        {{-- FOOTER - Totals --}}
        <div class="bg-white rounded-lg shadow p-4 mb-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-3 border-b pb-2">Tổng cộng</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                <div class="bg-gray-50 rounded p-2">
                    <span class="text-gray-500">Tổng tiền (NT)</span>
                    <p class="font-mono font-semibold">{{ number_format($pT_tien_nt0, 0, ',', '.') }}</p>
                </div>
                <div class="bg-gray-50 rounded p-2">
                    <span class="text-gray-500">Tổng tiền (VND)</span>
                    <p class="font-mono font-semibold">{{ number_format($pT_tien0, 0, ',', '.') }}</p>
                </div>
                <div class="bg-gray-50 rounded p-2">
                    <span class="text-gray-500">Chi phí (NT)</span>
                    <p class="font-mono font-semibold">{{ number_format($pT_cp_nt, 0, ',', '.') }}</p>
                </div>
                <div class="bg-gray-50 rounded p-2">
                    <span class="text-gray-500">Chi phí (VND)</span>
                    <p class="font-mono font-semibold">{{ number_format($pT_cp, 0, ',', '.') }}</p>
                </div>
                <div class="bg-gray-50 rounded p-2">
                    <span class="text-gray-500">Thuế (NT)</span>
                    <p class="font-mono font-semibold">{{ number_format($pT_thue_nt, 0, ',', '.') }}</p>
                </div>
                <div class="bg-gray-50 rounded p-2">
                    <span class="text-gray-500">Thuế (VND)</span>
                    <p class="font-mono font-semibold">{{ number_format($pT_thue, 0, ',', '.') }}</p>
                </div>
                <div class="bg-blue-50 rounded p-2 col-span-2">
                    <span class="text-blue-700">Tổng thanh toán (VND)</span>
                    <p class="font-mono font-bold text-base">{{ number_format($pT_tt, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex justify-end gap-2 mb-8">
            <a href="{{ route('muahang.hoadonmua') }}"
                class="px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                Hủy
            </a>
            <button type="submit"
                class="px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500">
                Lưu
            </button>
        </div>
    </form>
</div>
