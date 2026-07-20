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
                            placeholder="Số HĐ, số CT, mã NCC..."
                            class="w-full rounded-md border border-gray-200 px-3 py-1.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        <x-input-error for="pSearch" class="mt-1" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Kỳ báo cáo</label>
                    <div class="col-span-2">
                        @livewire('catalog::component.timer', key('povchpo3-timer-' . $timerKey))
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Mã nhà cung cấp</label>
                    <div class="col-span-2">
                        <livewire:catalog::component.input-khachhang mode="nhacungcap" wire:model="pMa_kh" />
                        <x-input-error for="pMa_kh" class="mt-1" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4 pt-1">
                    <span></span>
                    <div class="col-span-2 flex flex-wrap items-center gap-2">
                        <x-button-loading
                            class="rounded-md bg-blue-600 px-3 py-1.5 text-sm text-white hover:bg-blue-700"
                            wire:click="loadData">
                            Thực hiện
                        </x-button-loading>
                        <button type="button"
                            class="rounded-md border border-gray-300 px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50"
                            wire:click="resetFilters">
                            Xóa lọc
                        </button>
                        <a href="{{ route('po.vch.povchpo3.create') }}"
                            class="rounded-md bg-blue-600 px-3 py-1.5 text-sm text-white hover:bg-blue-700">
                            Thêm mới
                        </a>
                    </div>
                </div>
            </div>

            <div x-show="activeTab === 'content'" class="w-full overflow-x-auto py-2">
                @if ([] === $invoices)
                    <div class="rounded border border-gray-200 bg-gray-50 p-4 text-sm text-gray-600">
                        Chưa có dữ liệu. Nhập điều kiện lọc rồi bấm Thực hiện.
                    </div>
                @else
                    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                        <div class="max-h-[calc(100vh-280px)] overflow-y-auto">
                            <table class="min-w-max text-left text-xs">
                                <thead class="sticky top-0 z-10 bg-gray-50 text-gray-500">
                                    <tr>
                                        <th class="border-b border-gray-200 px-2 py-2 text-left font-medium">Chi tiết</th>
                                        <th class="border-b border-gray-200 px-2 py-2 text-right font-medium">#</th>
                                        <th class="border-b border-gray-200 px-2 py-2 text-left font-medium">Số CT</th>
                                        <th class="border-b border-gray-200 px-2 py-2 text-left font-medium">Ngày CT</th>
                                        <th class="border-b border-gray-200 px-2 py-2 text-left font-medium">Số HĐ</th>
                                        <th class="border-b border-gray-200 px-2 py-2 text-left font-medium">Ngày HĐ</th>
                                        <th class="border-b border-gray-200 px-2 py-2 text-left font-medium">Mã NCC</th>
                                        <th class="border-b border-gray-200 px-2 py-2 text-left font-medium">Diễn giải</th>
                                        <th class="border-b border-gray-200 px-2 py-2 text-right font-medium">Tổng tiền</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($invoices as $invoice)
                                        <tr class="hover:bg-sky-50">
                                            <td class="whitespace-nowrap px-2 py-2">
                                                <a href="{{ route('po.vch.povchpo3.edit', $invoice->stt_rec) }}"
                                                    class="rounded border border-gray-200 px-2 py-1 text-xs font-medium text-gray-700 hover:border-sky-300 hover:text-sky-700">
                                                    Sửa
                                                </a>
                                            </td>
                                            <td class="whitespace-nowrap px-2 py-2 text-right tabular-nums text-gray-400">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td class="whitespace-nowrap px-2 py-2 font-mono text-gray-700">{{ $invoice->so_ct }}</td>
                                            <td class="whitespace-nowrap px-2 py-2 text-gray-700">{{ optional($invoice->ngay_ct)->format('d/m/Y') }}</td>
                                            <td class="whitespace-nowrap px-2 py-2 font-mono text-gray-700">{{ $invoice->so_hd }}</td>
                                            <td class="whitespace-nowrap px-2 py-2 text-gray-700">{{ optional($invoice->ngay_hd)->format('d/m/Y') }}</td>
                                            <td class="whitespace-nowrap px-2 py-2 font-mono text-gray-700">{{ $invoice->ma_kh }}</td>
                                            <td class="px-2 py-2 text-gray-700">
                                                <div class="max-w-[420px] truncate">{{ $invoice->dien_giai ?? '' }}</div>
                                            </td>
                                            <td class="whitespace-nowrap px-2 py-2 text-right font-mono text-gray-700">
                                                {{ number_format((float) ($invoice->t_tt ?? 0), 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-500">
                            <span>{{ count($invoices) }} dòng dữ liệu</span>
                        </div>
                    </div>
                @endif
            </div>
        </x-slot:content>
    </x-nav-tabs>
</div>
