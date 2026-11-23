<div class="space-y-4">
    <!-- Header Form -->
    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
        <h4 class="mb-3 text-sm font-semibold uppercase tracking-wide text-gray-700">Thông tin phiếu</h4>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <!-- Mã KH -->
            <div class="grid grid-cols-4 items-center gap-2">
                <label class="col-span-1 text-right text-sm font-medium text-gray-700">Mã KH</label>
                <div class="col-span-3">
                    <livewire:catalog::component.input-khachhang mode="khachhang,nhacungcap" wire:model.live="pMa_Kh"
                        placeholder="Chọn khách hàng..." />
                </div>
            </div>

            <!-- Diễn giải -->
            <div class="grid grid-cols-4 items-center gap-2">
                <label class="col-span-1 text-right text-sm font-medium text-gray-700">Diễn giải</label>
                <input
                    class="col-span-3 rounded-md border-gray-300 py-1.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    wire:model.live="pDien_Giai" placeholder="Nội dung giao dịch..." />
            </div>

            <!-- Địa chỉ -->
            <div class="grid grid-cols-4 items-center gap-2">
                <label class="col-span-1 text-right text-sm font-medium text-gray-700">Địa chỉ</label>
                <input
                    class="col-span-3 rounded-md border-gray-300 py-1.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    wire:model="pDia_Chi" placeholder="Địa chỉ khách hàng..." />
            </div>

            <!-- Người GD -->
            <div class="grid grid-cols-4 items-center gap-2">
                <label class="col-span-1 text-right text-sm font-medium text-gray-700">Người GD</label>
                <input
                    class="col-span-3 rounded-md border-gray-300 py-1.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    wire:model="pNguoi_Gd" />
            </div>

            <!-- TK có -->
            <div class="grid grid-cols-4 items-center gap-2">
                <label class="col-span-1 text-right text-sm font-medium text-gray-700">TK có</label>
                <div class="col-span-3">
                    <livewire:catalog::component.input-taikhoan wire:model.live="pTk_Co" />
                </div>
            </div>

            <!-- Số CT -->
            <div class="grid grid-cols-4 items-center gap-2">
                <label class="col-span-1 text-right text-sm font-medium text-gray-700">Số CT</label>
                <input
                    class="col-span-3 rounded-md border-gray-300 py-1.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    wire:model="pSo_Ct" />
            </div>

            <!-- Ngày CT -->
            <div class="grid grid-cols-4 items-center gap-2">
                <label class="col-span-1 text-right text-sm font-medium text-gray-700">Ngày CT</label>
                <div class="col-span-3">
                    <x-catalog::input-date class="block w-full px-2 py-1.5 text-sm" wire:model="pNgay_Ct" />
                </div>
            </div>

            <!-- Ngày lập -->
            <div class="grid grid-cols-4 items-center gap-2">
                <label class="col-span-1 text-right text-sm font-medium text-gray-700">Ngày lập</label>
                <div class="col-span-3">
                    <x-catalog::input-date class="block w-full px-2 py-1.5 text-sm" wire:model="pNgay_Lap" />
                </div>
            </div>
        </div>
    </div>

    <!-- Chi tiết phiếu -->
    <div class="rounded-lg border border-gray-200 bg-white">
        <div class="border-b border-gray-200 bg-gray-50 px-4 py-3">
            <h4 class="text-sm font-semibold uppercase tracking-wide text-gray-700">Chi tiết khoản nợ</h4>
        </div>

        <div class="p-4">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="whitespace-nowrap px-3 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                            TK Nợ
                        </th>
                        <th scope="col"
                            class="whitespace-nowrap px-3 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                            Diễn giải
                        </th>
                        <th scope="col"
                            class="whitespace-nowrap px-3 py-2 text-right text-xs font-medium uppercase tracking-wider text-gray-500">
                            Số dư
                        </th>
                        <th scope="col"
                            class="whitespace-nowrap px-3 py-2 text-right text-xs font-medium uppercase tracking-wider text-gray-500">
                            Số tiền
                        </th>
                        <th scope="col"
                            class="w-10 px-3 py-2 text-center text-xs font-medium uppercase tracking-wider text-gray-500">
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach ($pCts as $index => $row)
                        <tr class="hover:bg-gray-50">
                            <td class="whitespace-nowrap px-3 py-2">
                                <livewire:catalog::component.input-taikhoan wire:model.live="pCts.{{ $index }}.ma_tk"
                                    wire:key="tk-no-{{ $index }}" placeholder="Chọn TK..." />
                            </td>
                            <td class="px-3 py-2">
                                <input type="text"
                                    class="block w-full rounded-md border-gray-300 bg-gray-50 py-1.5 text-sm shadow-sm"
                                    wire:model="pCts.{{ $index }}.dien_giai" readonly>
                            </td>
                            <td class="whitespace-nowrap px-3 py-2 text-right text-sm">
                                @php
                                    $soDuDong = $pSoDuCts[$index] ?? 0;
                                @endphp
                                <span class="{{ $soDuDong >= 0 ? 'text-green-600' : 'text-red-600' }} font-medium">
                                    {{ number_format($soDuDong, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-3 py-2">
                                <input type="number"
                                    class="block w-full rounded-md border-gray-300 py-1.5 text-right text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    wire:model.lazy="pCts.{{ $index }}.ps_no"
                                    wire:keydown.enter="handleEnter({{ $index }})" placeholder="0">
                            </td>
                            <td class="whitespace-nowrap px-3 py-2 text-center">
                                <button wire:click="removeRow({{ $index }})"
                                    class="rounded p-1 text-red-500 hover:bg-red-50 hover:text-red-700"
                                    title="Xóa dòng">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3"
                            class="whitespace-nowrap px-3 py-3 text-right text-sm font-bold uppercase tracking-wide text-gray-700">
                            Tổng cộng:
                        </td>
                        <td class="whitespace-nowrap px-3 py-3 text-right text-sm font-bold text-gray-900">
                            {{ number_format($pTong_Ps_No, 0, ',', '.') }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between border-t border-gray-200 bg-gray-50 px-4 py-3">
            <button wire:click="addRow"
                class="inline-flex items-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium leading-4 text-blue-700 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Thêm dòng
            </button>

            <div class="flex items-center space-x-3">
                <!-- Message -->
                <x-catalog::action-message on="phieu-saved" class="text-sm text-green-600">
                    Đã lưu phiếu thành công
                </x-catalog::action-message>

                <x-catalog::action-message on="phieu-deleted" class="text-sm text-green-600">
                    Đã xóa phiếu thành công
                </x-catalog::action-message>

                <!-- Cancel Button (chỉ hiện khi đang sửa) -->
                @if ($pMode === 'edit')
                    <button wire:click="resetForm"
                        class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Hủy
                    </button>
                @endif

                <!-- Save Button -->
                <button wire:click="submit"
                    class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    {{ $pMode === 'edit' ? 'Cập nhật' : 'Lưu phiếu' }}
                </button>
            </div>
        </div>

        <!-- Validation Errors -->
        @error('pCts')
            <div class="border-t border-gray-200 bg-red-50 px-4 py-3">
                <p class="text-sm text-red-600">{{ $message }}</p>
            </div>
        @enderror

        @if ($errors->has('pSo_Ct'))
            <div class="border-t border-gray-200 bg-red-50 px-4 py-3">
                <p class="text-sm text-red-600">{{ $errors->first('pSo_Ct') }}</p>
            </div>
        @endif
    </div>
</div>
