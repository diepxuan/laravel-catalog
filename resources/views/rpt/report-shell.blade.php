{{--
    Shell dùng chung cho mọi màn BÁO CÁO.

    View của từng báo cáo chỉ cần:
        @include('catalog::rpt.report-shell', ['filterView' => 'catalog::gl.rpt.filters.glrptbct01'])

    hoặc dùng slot:
        <x-report-shell>
            <x-slot:filter> ...field lọc... </x-slot:filter>
        </x-report-shell>
--}}
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
                @isset($filterView)
                    @include($filterView)
                @else
                    {{ $filter ?? '' }}
                @endisset

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
                                        <dt class="truncate text-xs text-sky-700" title="{{ $column }}">{{ $this->columnLabel($column) }}</dt>
                                        <dd class="break-words font-mono text-xs text-sky-950">{{ $this->displayValue($value) }}</dd>
                                    </div>
                                @endforeach
                            </dl>
                        </div>
                    @endif

                    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                        <div class="max-h-[calc(100vh-280px)] overflow-x-auto overflow-y-auto">
                            <table class="w-full text-sm">
                                <thead class="sticky top-0 bg-gray-50">
                                    <tr>
                                        <th class="border-b px-3 py-2 text-right text-xs font-semibold text-gray-500">#</th>
                                        @foreach ($columns as $column)
                                            <th class="border-b px-3 py-2 text-xs font-semibold text-gray-500 {{ $this->isNumericColumn($column) ? 'text-right' : 'text-left' }}"
                                                title="{{ $column }}">
                                                {{ $this->columnLabel($column) }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rows as $index => $row)
                                        <tr class="cursor-pointer hover:bg-sky-50" wire:click="selectRow({{ $index }})">
                                            <td class="border-b px-3 py-1 text-right text-xs text-gray-400">{{ $index + 1 }}</td>
                                            @foreach ($columns as $column)
                                                <td class="border-b px-3 py-1 text-xs text-gray-700 {{ $this->isNumericColumn($column) ? 'text-right font-mono' : 'text-left' }}">
                                                    {{ $this->displayValue($row[$column] ?? null) }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </x-slot:content>
    </x-nav-tabs>
</div>
