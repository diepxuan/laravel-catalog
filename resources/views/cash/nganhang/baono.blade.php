<div class="phieuthu-container w-full space-y-6">
    <!-- Header Section -->
    <x-head-title>{{ 'Báo nợ - Tiền tệ' }}</x-head-title>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ 'Ngân hàng' }}
        </h2>
        <p>{{ 'Báo nợ' }}</p>
        <p class="mt-1 text-sm text-gray-600">{{ 'Quản lý phiếu báo nợ ngân hàng' }}</p>
    </x-slot>

    <!-- Phiếu báo nợ Form -->
    <div class="rounded-lg bg-white p-4 shadow-sm">
        <h3 class="mb-4 text-lg font-semibold text-gray-800">Tạo phiếu báo nợ mới</h3>
        <livewire:catalog::cash.nganhang.baono.phieubaono wire:model="newCaPh" />
    </div>

    <!-- Filter and Data Section -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
        <!-- Filter Panel -->
        <div class="rounded-lg bg-white p-4 shadow-sm lg:col-span-1">
            <h3 class="mb-4 text-lg font-semibold text-gray-800">Lọc dữ liệu</h3>

            <div class="space-y-4">
                <!-- Kỳ báo cáo -->
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Kỳ báo cáo</label>
                    <div class="w-full">
                        @livewire('catalog::component.timer')
                    </div>
                </div>

                <!-- TK nợ -->
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">TK nợ</label>
                    <livewire:catalog::component.input-taikhoan wire:model="pTk_List" />
                </div>

                <!-- TK có -->
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">TK có</label>
                    <livewire:catalog::component.input-taikhoan wire:model="pTkdu_List" />
                </div>

                <!-- Mã BP -->
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Mã BP</label>
                    <input
                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        wire:model="pMa_Bp" placeholder="Nhập mã bộ phận..." />
                </div>

                <!-- Mã NT -->
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Mã NT</label>
                    <input
                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        wire:model="pMa_Nt" placeholder="Nhập mã ngoại tệ..." />
                </div>

                <!-- Action Button -->
                <div class="pt-2">
                    <button
                        class="w-full rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        wire:click="submit">
                        Thực hiện
                    </button>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="rounded-lg bg-white p-4 shadow-sm lg:col-span-3">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Danh sách chứng từ</h3>
                <div class="text-sm text-gray-500">
                    Tổng: {{ count($glCts ?? []) }} bản ghi
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                STT</th>
                            <th scope="col"
                                class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Ngày CT</th>
                            <th scope="col"
                                class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Số CT</th>
                            <th scope="col"
                                class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Diễn giải</th>
                            <th scope="col"
                                class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Khách hàng</th>
                            <th scope="col"
                                class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                TK nợ</th>
                            <th scope="col"
                                class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                TK có</th>
                            <th scope="col"
                                class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Phát sinh</th>
                            <th scope="col"
                                class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Mã CT</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($glCts ?? [] as $glCt)
                            <tr @class([
                                'hover:bg-gray-50 transition-colors duration-150',
                                'bg-blue-50 hover:bg-blue-100' => $glCt->bold ?? false,
                            ])>
                                <td class="whitespace-nowrap px-3 py-3 text-sm text-gray-900">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 text-sm text-gray-900">
                                    {{ $glCt->ngay_ct }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 text-sm text-gray-900">
                                    {{ $glCt->so_ct }}
                                </td>
                                <td class="px-3 py-3 text-sm text-gray-900">
                                    <div class="max-w-xs truncate" title="{{ $glCt->dien_giai }}">
                                        {{ $glCt->dien_giai }}
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 text-sm text-gray-900">
                                    {{ $glCt->ten_kh }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 text-right text-sm text-gray-900">
                                    {{ $glCt->tk_no }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 text-right text-sm text-gray-900">
                                    {{ $glCt->tk_co }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 text-right text-sm font-medium text-gray-900">
                                    @currency($glCt->ps_no)
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 text-sm text-gray-900">
                                    <span
                                        class="inline-flex rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-800">
                                        {{ $glCt->ma_ct }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-3 py-8 text-center text-sm text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="mb-2 h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <p>Không có dữ liệu chứng từ</p>
                                        <p class="text-xs">Sử dụng bộ lọc để tìm kiếm</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
