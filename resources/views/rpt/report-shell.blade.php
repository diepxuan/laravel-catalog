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
                                        <tr @class([
                                            'cursor-pointer hover:bg-sky-50',
                                            'bg-sky-50' => ($selectedRowIndex ?? null) === $index,
                                        ]) wire:click="selectRow({{ $index }})">
                                            <td class="border-b px-3 py-1 text-right text-xs text-gray-400">{{ $index + 1 }}</td>
                                            @foreach ($columns as $column)
                                                <td class="border-b px-3 py-1 text-xs text-gray-700 {{ $this->isNumericColumn($column) ? 'text-right font-mono' : 'text-left' }}">
                                                    {{ $this->displayValue($row[$column] ?? null, $column) }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if ([] !== $selectedRow)
                        @php($detailRows = $this->selectedDetailRows())
                        <div class="mt-4 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                            <div class="flex items-center justify-between border-b border-gray-200 bg-gray-50 px-3 py-2">
                                <span class="text-sm font-medium text-gray-700">
                                    Chi tiết phiếu #{{ ($selectedRowIndex ?? 0) + 1 }}
                                </span>
                                <span class="flex items-center gap-2 font-mono text-xs text-gray-500">
                                    {{ $this->displayValue($selectedRow['so_ct'] ?? '', 'so_ct') }}
                                    <button type="button" class="rounded px-1 text-gray-400 hover:text-gray-700"
                                        title="Bỏ chọn" wire:click="clearSelectedRow">&times;</button>
                                </span>
                            </div>

                            {{-- Phan 1: THONG TIN phieu (bang thong tin cu, giu nguyen) --}}
                            <div class="border-b border-gray-200">
                                <div class="bg-gray-50 px-3 py-1.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Thông tin
                                </div>
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-1 px-3 py-2 md:grid-cols-2 xl:grid-cols-3">
                                    @foreach ($selectedRow as $column => $value)
                                        <div class="grid grid-cols-[120px_minmax(0,1fr)] gap-2">
                                            <dt class="truncate text-xs text-gray-500" title="{{ $column }}">{{ $this->columnLabel($column) }}</dt>
                                            <dd class="break-words font-mono text-xs text-gray-800">{{ $this->displayValue($value, $column) }}</dd>
                                        </div>
                                    @endforeach
                                </dl>
                            </div>

                            {{-- Phan 2: CAC DONG CHI TIET (chi hien khi bao cao co Master-Detail) --}}
                            @if ($this->hasDetail())
                                <div>
                                    <div class="bg-gray-50 px-3 py-1.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        Các dòng chi tiết
                                    </div>
                                    @if ([] === $detailRows)
                                        <div class="px-3 py-4 text-xs text-gray-500">
                                            Phiếu này không có dòng chi tiết.
                                        </div>
                                    @else
                                        <div class="max-h-[calc(100vh-420px)] overflow-x-auto overflow-y-auto">
                                            <table class="w-full text-sm">
                                                <thead class="sticky top-0 bg-gray-50">
                                                    <tr>
                                                        <th class="border-b px-3 py-2 text-right text-xs font-semibold text-gray-500">#</th>
                                                        @foreach ($detailColumns as $column)
                                                            <th class="border-b px-3 py-2 text-xs font-semibold text-gray-500 {{ $this->isNumericColumn($column) ? 'text-right' : 'text-left' }}"
                                                                title="{{ $column }}">
                                                                {{ $this->detailColumnLabel($column) }}
                                                            </th>
                                                        @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($detailRows as $detailIndex => $detailRow)
                                                        <tr class="hover:bg-sky-50">
                                                            <td class="border-b px-3 py-1 text-right text-xs text-gray-400">{{ $detailIndex + 1 }}</td>
                                                            @foreach ($detailColumns as $column)
                                                                <td class="border-b px-3 py-1 text-xs text-gray-700 {{ $this->isNumericColumn($column) ? 'text-right font-mono' : 'text-left' }}">
                                                                    {{ $this->displayDetailValue($detailRow[$column] ?? null, $column) }}
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="border-t border-gray-200 bg-gray-50 px-3 py-1.5 text-xs text-gray-500">
                                            {{ number_format(count($detailRows)) }} dòng chi tiết
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endif
                @endif
            </div>
        </x-slot:content>
    </x-nav-tabs>
</div>
