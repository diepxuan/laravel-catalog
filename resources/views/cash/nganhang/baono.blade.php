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

    <!-- Messages -->
    <x-catalog::action-message on="phieu-deleted" class="rounded-md bg-green-50 p-4 text-sm text-green-600">
        Đã xóa phiếu thành công
    </x-catalog::action-message>

    <x-catalog::action-message on="delete-error" class="rounded-md bg-red-50 p-4 text-sm text-red-600">
        Không thể xóa phiếu
    </x-catalog::action-message>

    <!-- Phiếu báo nợ Form -->
    <div class="rounded-lg bg-white p-4 shadow-sm">
        <div class="mb-4 flex items-center justify-between border-b border-gray-200 pb-3">
            <h3 class="text-lg font-semibold text-gray-800">
                @if ($editingSttRec)
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5 text-blue-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Sửa phiếu báo nợ
                    </span>
                @else
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5 text-green-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tạo phiếu báo nợ mới
                    </span>
                @endif
            </h3>

            @if ($editingSttRec)
                <button wire:click="closeForm"
                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Đóng
                </button>
            @endif
        </div>

        <livewire:catalog::cash.nganhang.baono.phieubaono :stt-rec="$editingSttRec"
            wire:key="phieubaono-{{ $editingSttRec ?: 'new' }}" />
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
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 inline h-4 w-4" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Lọc
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
                                class="px-3 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">
                                TK nợ</th>
                            <th scope="col"
                                class="px-3 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">
                                TK có</th>
                            <th scope="col"
                                class="px-3 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">
                                Phát sinh</th>
                            <th scope="col"
                                class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Mã CT</th>
                            <th scope="col"
                                class="px-3 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500">
                                Thao tác</th>
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
                                <td class="whitespace-nowrap px-3 py-3 text-center text-sm">
                                    <div class="flex items-center justify-center space-x-2">
                                        <!-- Edit Button -->
                                        <button wire:click="editPhieu('{{ $glCt->stt_rec ?? '' }}')"
                                            class="rounded p-1 text-blue-600 hover:bg-blue-50 hover:text-blue-800"
                                            title="Sửa phiếu">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>

                                        <!-- Delete Button -->
                                        <button wire:click="deletePhieu('{{ $glCt->stt_rec ?? '' }}')"
                                            wire:confirm="Bạn có chắc chắn muốn xóa phiếu này?"
                                            class="rounded p-1 text-red-600 hover:bg-red-50 hover:text-red-800"
                                            title="Xóa phiếu">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-3 py-8 text-center text-sm text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="mb-2 h-12 w-12 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
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
