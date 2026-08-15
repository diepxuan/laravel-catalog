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
                    <label class="text-right text-sm text-gray-700">Tìm kiếm</label>
                    <div class="col-span-2">
                        <input wire:model.defer="pSearch"
                            placeholder="Số HĐ, số CT, mã KH..."
                            class="w-full rounded-md border border-gray-200 px-3 py-1.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        <x-input-error for="pSearch" class="mt-1" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Kỳ báo cáo</label>
                    <div class="col-span-2">
                        @livewire('catalog::component.timer', key('sovchso3-timer-' . $timerKey))
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Mã khách hàng</label>
                    <div class="col-span-2">
                        <livewire:catalog::component.input-khachhang mode="khachhang" wire:model="pMa_kh" />
                        <x-input-error for="pMa_kh" class="mt-1" />
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
                        <button type="button"
                            class="rounded-md border border-gray-300 px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50"
                            wire:click="resetFilters">
                            Xóa lọc
                        </button>
                        <a href="{{ simbaroute('so.vch.sovchso3.create') }}"
                            class="rounded-md bg-blue-600 px-3 py-1.5 text-sm text-white hover:bg-blue-700">
                            Thêm mới
                        </a>
                    </div>
                </div>
            </div>

            <div x-show="activeTab === 'content'" class="w-full overflow-x-auto py-2">
                @if ([] === $phieuRows)
                    <div class="rounded border border-gray-200 bg-gray-50 p-4 text-sm text-gray-600">
                        Chưa có dữ liệu. Nhập điều kiện lọc rồi bấm Thực hiện.
                    </div>
                @else
                    <div class="space-y-4">
                        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                        <div class="max-h-[calc(100vh-280px)] overflow-y-auto">
                            <table class="min-w-max text-left text-xs">
                                <thead class="sticky top-0 z-10 bg-gray-50 text-gray-500">
                                    <tr>
                                        <th class="border-b border-gray-200 px-2 py-2 text-right font-medium">#</th>
                                        <th class="border-b border-gray-200 px-2 py-2 text-left font-medium">Số CT</th>
                                        <th class="border-b border-gray-200 px-2 py-2 text-left font-medium">Ngày CT</th>
                                        <th class="border-b border-gray-200 px-2 py-2 text-left font-medium">Số seri</th>
                                        <th class="border-b border-gray-200 px-2 py-2 text-left font-medium">Mã KH</th>
                                        <th class="border-b border-gray-200 px-2 py-2 text-left font-medium">Tên khách hàng</th>
                                        <th class="border-b border-gray-200 px-2 py-2 text-left font-medium">Diễn giải</th>
                                        <th class="border-b border-gray-200 px-2 py-2 text-right font-medium">Tổng tiền</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($phieuRows as $phieu)
                                        <tr wire:click="selectPhieu({{ $loop->index }})"
                                            class="cursor-pointer {{ $selectedPhieuIndex === $loop->index ? 'bg-sky-50' : 'hover:bg-sky-50' }}">
                                            <td class="whitespace-nowrap px-2 py-2 text-right tabular-nums text-gray-400">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td class="whitespace-nowrap px-2 py-2 font-mono text-gray-700">{{ $phieu['so_ct'] }}</td>
                                            <td class="whitespace-nowrap px-2 py-2 text-gray-700">{{ \Illuminate\Support\Carbon::parse($phieu['ngay_ct'])->format('d/m/Y') }}</td>
                                            <td class="whitespace-nowrap px-2 py-2 font-mono text-gray-700">{{ $phieu['so_seri'] ?? '' }}</td>
                                            <td class="whitespace-nowrap px-2 py-2 font-mono text-gray-700">{{ $phieu['ma_kh'] }}</td>
                                            <td class="px-2 py-2 text-gray-700">{{ $phieu['ten_kh'] ?? '' }}</td>
                                            <td class="px-2 py-2 text-gray-700">
                                                <div class="max-w-[420px] truncate">{{ $phieu['dien_giai'] ?? '' }}</div>
                                            </td>
                                            <td class="whitespace-nowrap px-2 py-2 text-right font-mono text-gray-700">
                                                {{ number_format((float) ($phieu['t_tt'] ?? 0), 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-500">
                            <span>{{ count($phieuRows) }} dòng dữ liệu</span>
                            <button type="button"
                                class="rounded-md bg-gray-700 px-2.5 py-1 text-xs text-white hover:bg-gray-800"
                                wire:click="exportCsv">
                                Xuất Excel
                            </button>
                        </div>
                        </div>

                        @if ([] !== $selectedPhieu)
                            <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                            <div class="flex items-center justify-between border-b border-gray-200 bg-gray-50 px-3 py-2">
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Chi tiết phiếu #{{ ($selectedPhieuIndex ?? 0) + 1 }}</span>
                                    <span class="ml-2 font-mono text-xs text-gray-500">{{ $selectedPhieu['so_ct'] ?? '' }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ simbaroute('so.vch.sovchso3.edit', $selectedPhieu['stt_rec']) }}"
                                        class="rounded bg-yellow-100 px-2 py-1 text-xs text-yellow-700 hover:bg-yellow-200">
                                        Sửa
                                    </a>
                                    <button type="button"
                                        wire:click="deleteInvoice('{{ $selectedPhieu['stt_rec'] }}')"
                                        wire:confirm="Bạn có chắc chắn muốn xóa hóa đơn {{ $selectedPhieu['so_ct'] }}?"
                                        class="rounded bg-red-100 px-2 py-1 text-xs text-red-700 hover:bg-red-200">
                                        Xóa
                                    </button>
                                </div>
                            </div>
                            @if ([] !== $chiTietFiltered)
                                <div class="max-h-[calc(100vh-420px)] overflow-y-auto">
                                    <table class="min-w-max text-left text-xs">
                                        <thead class="sticky top-0 z-10 bg-gray-50 text-gray-500">
                                            <tr>
                                                <th class="border-b border-gray-200 px-2 py-2 text-right font-medium">#</th>
                                                <th class="border-b border-gray-200 px-2 py-2 text-left font-medium">Mã VT</th>
                                                <th class="border-b border-gray-200 px-2 py-2 text-left font-medium">Tên VT</th>
                                                <th class="border-b border-gray-200 px-2 py-2 text-left font-medium">DVT</th>
                                                <th class="border-b border-gray-200 px-2 py-2 text-left font-medium">Kho</th>
                                                <th class="border-b border-gray-200 px-2 py-2 text-right font-medium">Số lượng</th>
                                                <th class="border-b border-gray-200 px-2 py-2 text-right font-medium">Giá NT</th>
                                                <th class="border-b border-gray-200 px-2 py-2 text-right font-medium">Thành tiền NT</th>
                                                <th class="border-b border-gray-200 px-2 py-2 text-right font-medium">Thuế NT</th>
                                                <th class="border-b border-gray-200 px-2 py-2 text-right font-medium">Tổng NT</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            @foreach ($chiTietFiltered as $detail)
                                                <tr class="hover:bg-sky-50">
                                                    <td class="px-2 py-2 text-right tabular-nums text-gray-400">{{ $loop->iteration }}</td>
                                                    <td class="whitespace-nowrap px-2 py-2 font-mono text-gray-700">{{ $detail['ma_vt'] ?? '' }}</td>
                                                    <td class="px-2 py-2 text-gray-700">{{ $detail['ten_vt'] ?? '' }}</td>
                                                    <td class="whitespace-nowrap px-2 py-2 text-gray-700">{{ $detail['dvt'] ?? '' }}</td>
                                                    <td class="whitespace-nowrap px-2 py-2 text-gray-700">{{ $detail['ma_kho'] ?? '' }}</td>
                                                    <td class="whitespace-nowrap px-2 py-2 text-right tabular-nums text-gray-700">{{ number_format((float) ($detail['so_luong'] ?? 0), 2, ',', '.') }}</td>
                                                    <td class="whitespace-nowrap px-2 py-2 text-right tabular-nums text-gray-700">{{ number_format((float) ($detail['gia_nt2'] ?? 0), 2, ',', '.') }}</td>
                                                    <td class="whitespace-nowrap px-2 py-2 text-right tabular-nums text-gray-700">{{ number_format((float) ($detail['tien_nt2'] ?? 0), 2, ',', '.') }}</td>
                                                    <td class="whitespace-nowrap px-2 py-2 text-right tabular-nums text-gray-700">{{ number_format((float) ($detail['thue_gtgt_nt'] ?? 0), 2, ',', '.') }}</td>
                                                    <td class="whitespace-nowrap px-2 py-2 text-right tabular-nums text-gray-700">{{ number_format((float) ($detail['tt_nt'] ?? 0), 2, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="border-t border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-500">
                                    {{ count($chiTietFiltered) }} dòng chi tiết
                                </div>
                            @else
                                <div class="p-4 text-sm text-gray-600">Phiếu không có chi tiết.</div>
                            @endif
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </x-slot:content>
    </x-nav-tabs>
</div>
