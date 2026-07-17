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
                    <div
                        class="col-span-2 rounded-md border border-gray-200 bg-gray-50 px-3 py-1.5 text-sm text-gray-700">
                        {{ $pTieu_de }}
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Thời gian</label>
                    <div class="col-span-2">
                        @livewire('catalog::component.timer', key('arrptbccn01-timer'))
                        <x-input-error for="pNgay1" class="mt-1" />
                        <x-input-error for="pNgay2" class="mt-1" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Tài khoản</label>
                    <div class="col-span-2">
                        <livewire:catalog::component.input-taikhoan wire:model="pTk" filter="tk_cn=1" />
                        <x-input-error for="pTk" class="mt-1" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Mã nhà cung cấp</label>
                    <div class="col-span-2">
                        <livewire:catalog::component.input-khachhang mode="nhacungcap" wire:model="pMa_kh" />
                        <x-input-error for="pMa_kh" class="mt-1" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Mã ngoại tệ</label>
                    <div class="col-span-2">
                        <input class="w-full rounded-md border-gray-300 py-1 text-sm shadow-sm" wire:model="pMa_nt"
                            placeholder="Để trống = VND" />
                        <x-input-error for="pMa_nt" class="mt-1" />
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
                        @if ([] !== $rows)
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

                @if ([] === $rows)
                    <div class="rounded border border-gray-200 bg-gray-50 p-4 text-sm text-gray-600">
                        Chưa có dữ liệu. Nhập điều kiện lọc rồi bấm Thực hiện.
                    </div>
                @else
                    @if ([] !== $selectedRow)
                        <div class="mb-3 rounded border border-sky-200 bg-sky-50 p-3 text-sm text-sky-900">
                            <div class="mb-2 flex items-center justify-between gap-2">
                                <div class="font-medium">Chi tiết dòng {{ ($selectedRowIndex ?? 0) + 1 }}</div>
                                <button type="button" class="text-xs font-medium text-sky-700 hover:text-sky-900"
                                    wire:click="clearSelectedRow">
                                    Đóng
                                </button>
                            </div>
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-1 md:grid-cols-2 xl:grid-cols-3">
                                @foreach ($presentationColumns as $column)
                                    <div class="grid grid-cols-[120px_minmax(0,1fr)] gap-2">
                                        <dt class="truncate text-xs text-sky-700">{{ $column['label'] }}</dt>
                                        <dd class="break-words text-xs text-sky-950">
                                            {{ $this->cellValue($selectedRow, $column['key']) }}</dd>
                                    </div>
                                @endforeach
                            </dl>
                        </div>
                    @endif

                    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                        <div class="max-h-[calc(100vh-280px)] overflow-y-auto">
                            <table class="min-w-max text-left text-xs">
                                <thead class="sticky top-0 z-10 bg-gray-50 text-gray-500">
                                    <tr>
                                        <th class="border-b border-gray-200 px-2 py-2 text-left font-medium">Chi tiết
                                        </th>
                                        <th class="border-b border-gray-200 px-2 py-2 text-right font-medium">#</th>
                                        @foreach ($presentationColumns as $column)
                                            <th
                                                class="{{ $column['headerClass'] }} whitespace-nowrap border-b border-gray-200 px-2 py-2 font-medium">
                                                {{ $column['label'] }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($rows as $row)
                                        <tr
                                            class="{{ $selectedRowIndex === $loop->index ? 'bg-sky-50' : 'hover:bg-sky-50' }}">
                                            <td class="whitespace-nowrap px-2 py-2">
                                                <button type="button"
                                                    class="rounded border border-gray-200 px-2 py-1 text-xs font-medium text-gray-700 hover:border-sky-300 hover:text-sky-700"
                                                    wire:click="selectRow({{ $loop->index }})">
                                                    Xem
                                                </button>
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-2 py-2 text-right tabular-nums text-gray-400">
                                                {{ $loop->iteration }}
                                            </td>
                                            @foreach ($presentationColumns as $column)
                                                <td
                                                    class="{{ $column['key'] === 'dien_giai' ? $this->rowLineClass($row) : 'text-gray-700' }} {{ $column['cellClass'] }} px-2 py-2">
                                                    @if ('document' === $column['key'])
                                                        <div class="whitespace-nowrap text-gray-700">
                                                            {{ $this->documentDate($row) }}
                                                        </div>
                                                        <div class="mt-0.5 whitespace-nowrap text-[11px] text-gray-500">
                                                            {{ $this->documentSoCt($row) ?: '-' }}
                                                        </div>
                                                        @if ('' !== $this->documentSttRec($row))
                                                            <div
                                                                class="mt-0.5 max-w-[150px] truncate text-[10px] text-gray-400">
                                                                {{ $this->documentSttRec($row) }}
                                                            </div>
                                                        @endif
                                                    @elseif ('dien_giai' === $column['key'])
                                                        <div class="max-w-[420px] whitespace-normal leading-4">
                                                            {{ $this->displayValue(data_get($row, 'dien_giai') ?? data_get($row, 'Dien_giai')) }}
                                                        </div>
                                                        @if ('' !== $this->itemSummary($row))
                                                            <div
                                                                class="mt-1 max-w-[420px] whitespace-normal text-[11px] leading-4 text-gray-500">
                                                                {{ $this->itemSummary($row) }}
                                                            </div>
                                                        @endif
                                                    @else
                                                        {{ $this->cellValue($row, $column['key']) }}
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-500">
                            <span>{{ number_format(count($rows)) }} dòng dữ liệu</span>
                            <x-button-loading
                                class="rounded-md bg-gray-700 px-2.5 py-1 text-xs text-white hover:bg-gray-800"
                                wire:click="exportCsv">
                                Xuất Excel
                            </x-button-loading>
                        </div>
                    </div>
                @endif
            </div>
        </x-slot:content>
    </x-nav-tabs>
</div>
