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
                    <label class="text-right text-sm text-gray-700">Kiểu tồn</label>
                    <div class="col-span-2 inline-flex items-center text-sm text-gray-700">
                        <span class="pr-2">Đầu kỳ</span>
                        <x-input-switch wire:model="pDk_Ck" />
                        <span class="pl-2">Cuối kỳ</span>
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Ngày</label>
                    <div class="col-span-2">
                        <x-input-date class="w-full px-2 py-1 text-sm" wire:model="pNgay" />
                        <x-input-error for="pNgay" class="mt-1" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Mã kho</label>
                    <div class="col-span-2">
                        <livewire:catalog::component.input-indmkho wire:model="pMa_kho" />
                        <x-input-error for="pMa_kho" class="mt-1" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Mã vật tư</label>
                    <div class="col-span-2">
                        <livewire:catalog::component.input-indmvt wire:model="pMa_vt" />
                        <x-input-error for="pMa_vt" class="mt-1" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Nhóm vật tư</label>
                    <div class="col-span-2">
                        <livewire:catalog::component.input-indmnhvt wire:model="pMa_nhvt" />
                        <x-input-error for="pMa_nhvt" class="mt-1" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Mã vị trí</label>
                    <div class="col-span-2">
                        <input class="w-full rounded-md border-gray-300 py-1 text-sm shadow-sm" wire:model="pMa_vitri" />
                        <x-input-error for="pMa_vitri" class="mt-1" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">TK vật tư</label>
                    <div class="col-span-2">
                        <livewire:catalog::component.input-taikhoan wire:model="pTk_vt" />
                        <x-input-error for="pTk_vt" class="mt-1" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Mã lô</label>
                    <div class="col-span-2">
                        <input class="w-full rounded-md border-gray-300 py-1 text-sm shadow-sm" wire:model="pMa_lo" />
                        <x-input-error for="pMa_lo" class="mt-1" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Đơn vị tính</label>
                    <div class="col-span-2">
                        <x-select class="w-full rounded-md border-gray-300 py-1 text-sm shadow-sm" wire:model="pDVT"
                            :options="[
                                '0' => 'Đơn vị tính kho',
                                '1' => 'Đơn vị tính mua',
                                '2' => 'Đơn vị tính bán',
                            ]" />
                        <x-input-error for="pDVT" class="mt-1" />
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <label class="text-right text-sm text-gray-700">Ngoại tệ</label>
                    <div class="col-span-2 inline-flex items-center text-sm text-gray-700">
                        <x-input-switch wire:model="pNgoai_te" />
                        <span class="pl-2">In theo ngoại tệ</span>
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4">
                    <span></span>
                    <div class="col-span-2 space-y-2 text-sm text-gray-700">
                        <label class="inline-flex items-center">
                            <x-input-switch wire:model="pQuaToiThieu" />
                            <span class="pl-2">Tồn nhỏ hơn mức tối thiểu</span>
                        </label>
                        <label class="inline-flex items-center">
                            <x-input-switch wire:model="pQuaToiDa" />
                            <span class="pl-2">Tồn lớn hơn mức tối đa</span>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-3 items-center gap-4 pt-1">
                    <span></span>
                    <div class="col-span-2 flex flex-wrap items-center gap-2">
                        <x-button-loading class="rounded-md bg-blue-600 px-3 py-1.5 text-sm text-white hover:bg-blue-700"
                            wire:click="submit">
                            Thực hiện
                        </x-button-loading>
                        @if ([] !== $rows)
                            <x-button-loading class="rounded-md bg-gray-700 px-3 py-1.5 text-sm text-white hover:bg-gray-800"
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
                    <div class="mb-3 flex flex-wrap items-center justify-between gap-2 text-sm text-gray-600">
                        <span>{{ number_format(count($rows)) }} dòng dữ liệu</span>
                        <x-button-loading class="rounded-md bg-gray-700 px-3 py-1.5 text-sm text-white hover:bg-gray-800"
                            wire:click="exportCsv">
                            Xuất Excel
                        </x-button-loading>
                    </div>

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
                                @foreach ($selectedRow as $column => $value)
                                    <div class="grid grid-cols-[120px_minmax(0,1fr)] gap-2">
                                        <dt class="truncate font-mono text-xs text-sky-700">{{ $column }}</dt>
                                        <dd class="break-words font-mono text-xs text-sky-950">{{ $this->displayValue($value) }}</dd>
                                    </div>
                                @endforeach
                            </dl>
                        </div>
                    @endif

                    <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
                        <table class="min-w-max text-left text-xs">
                            <thead class="sticky top-0 z-10 bg-gray-50 text-gray-500">
                                <tr>
                                    <th class="border-b border-gray-200 px-2 py-2 text-left font-medium">Chi tiết</th>
                                    <th class="border-b border-gray-200 px-2 py-2 text-right font-medium">#</th>
                                    @foreach ($columns as $column)
                                        <th class="whitespace-nowrap border-b border-gray-200 px-2 py-2 font-medium {{ $this->isNumericColumn($column) ? 'text-right' : 'text-left' }}">
                                            {{ $column }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($rows as $row)
                                    <tr class="{{ $selectedRowIndex === $loop->index ? 'bg-sky-50' : 'hover:bg-sky-50' }}">
                                        <td class="whitespace-nowrap px-2 py-2">
                                            <button type="button" class="rounded border border-gray-200 px-2 py-1 text-xs font-medium text-gray-700 hover:border-sky-300 hover:text-sky-700"
                                                wire:click="selectRow({{ $loop->index }})">
                                                Xem
                                            </button>
                                        </td>
                                        <td class="whitespace-nowrap px-2 py-2 text-right font-mono text-gray-400 tabular-nums">
                                            {{ $loop->iteration }}
                                        </td>
                                        @foreach ($columns as $column)
                                            <td class="whitespace-nowrap px-2 py-2 text-gray-800 {{ $this->isNumericColumn($column) ? 'text-right font-mono tabular-nums' : '' }}">
                                                {{ $this->displayValue(data_get($row, $column)) }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </x-slot:content>
    </x-nav-tabs>
</div>
