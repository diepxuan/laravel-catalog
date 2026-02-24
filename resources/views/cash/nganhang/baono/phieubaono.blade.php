<div class="grid grid-cols-1 md:grid-cols-3 items-start gap-1 pb-3">
    <div class="">
        <div class="grid grid-cols-4 items-center gap-4 pt-1">
            <label class="text-right">Mã KH</label>
            <div class="col-span-3">
                <div class="relative">
                    <input type="text"
                        class="block w-full rounded-md border-gray-300 py-0 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        list="ArDmKh-suggestions" wire:model="pMa_Kh" wire:change="updateKhachHang" />
                </div>
            </div>
        </div>

        <div class="grid grid-cols-4 items-center gap-4 pt-1">
            <label class="text-right">Diễn giải</label>
            <input class="col-span-3 rounded-md border-gray-300 py-0 text-sm shadow-sm" wire:model="pDien_Giai" />
        </div>

        <div class="grid grid-cols-4 items-center gap-4 pt-1">
            <label class="text-right">Địa chỉ</label>
            <input class="col-span-3 rounded-md border-gray-300 py-0 text-sm shadow-sm" wire:model="pDia_Chi" />
        </div>

        <div class="grid grid-cols-4 items-center gap-4 pt-1">
            <label class="text-right">Người GD</label>
            <input class="col-span-3 rounded-md border-gray-300 py-0 text-sm shadow-sm" wire:model="pNguoi_Gd" />
        </div>

        <div class="grid grid-cols-4 items-center gap-4 pt-1">
            <label class="text-right">TK có</label>
            <div class="col-span-3">
                <livewire:catalog::component.input-taikhoan wire:model="pTk_Co" />
            </div>
        </div>

        <div class="grid grid-cols-4 items-center gap-4 pt-1">
            <label class="text-right">Số CTừ</label>
            <input class="col-span-3 rounded-md border-gray-300 py-0 text-sm shadow-sm" wire:model="pSo_Ct" />
        </div>

        <div class="grid grid-cols-4 items-center gap-4 pt-1">
            <label class="text-right">Ngày CTừ</label>
            <div class="col-span-3">
                <x-input-date class="block w-full px-2 py-0 text-sm" wire:model="pNgay_Ct" />
            </div>
        </div>

        <div class="grid grid-cols-4 items-center gap-4 pt-1">
            <label class="text-right">Ngày lập</label>
            <div class="col-span-3">
                <x-input-date class="block w-full px-2 py-0 text-sm" wire:model="pNgay_Lap" />
            </div>
        </div>
    </div>

    <div class="mt-4 md:col-span-2">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">TK Nợ</th>
                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diễn giải</th>
                    <th scope="col" class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Số dư</th>
                    <th scope="col" class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Số tiền</th>
                    <th scope="col" class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-10"></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($pCts as $index => $row)
                <tr>
                    <td class="px-3 py-2">
                        <div class="relative">
                            <input type="text"
                                class="block w-full rounded-md border-gray-300 py-0 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                list="GlDmTk-suggestions" 
                                wire:model.live.debounce.500ms="pCts.{{ $index }}.ma_tk" />
                            
                            <datalist id="GlDmTk-suggestions">
                                @foreach ($glDmTks as $glDmTk)
                                    <option value="{{ $glDmTk->tk }}">{{ $glDmTk->ten_tk }}</option>
                                @endforeach
                            </datalist>
                        </div>
                    </td>
                    <td class="px-3 py-2">
                        <input type="text" class="block w-full rounded-md border-gray-300 py-1 text-sm shadow-sm bg-gray-50" 
                               wire:model="pCts.{{ $index }}.dien_giai" readonly>
                    </td>
                    <td class="px-3 py-2 text-right text-sm">
                        @php
                            $soDuDong = $pSoDuCts[$index] ?? null;
                        @endphp
                        @if ($soDuDong !== null && is_numeric($soDuDong))
                            <span class="{{ $soDuDong >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($soDuDong, 0, ',', '.') }}
                            </span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-3 py-2">
                        <input type="number" class="block w-full rounded-md border-gray-300 py-1 text-sm shadow-sm text-right focus:border-indigo-500 focus:ring-indigo-500" wire:model.lazy="pCts.{{ $index }}.ps_no">
                    </td>
                    <td class="px-3 py-2 text-center">
                        <button wire:click="removeRow({{ $index }})" class="text-red-500 hover:text-red-700 focus:outline-none">
                            <span class="fas fa-trash"></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50">
                <tr>
                    <td colspan="3" class="px-3 py-2 text-right font-bold text-gray-700">Tổng cộng:</td>
                    <td class="px-3 py-2 text-right font-bold text-gray-900">{{ number_format($pTong_Ps_No, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <div class="mt-4 flex justify-between items-center">
             <button wire:click="addRow" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                + Thêm dòng
            </button>

            <div class="flex items-center space-x-2">
                @if (session()->has('message'))
                    <span class="text-green-600 text-sm font-medium animate-pulse">
                        {{ session('message') }}
                    </span>
                @endif

                <button wire:click="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Lưu phiếu
                </button>
            </div>
        </div>
        
        @error('pCts') <div class="mt-2 text-red-500 text-sm">{{ $message }}</div> @enderror
        @error('pMa_Kh') <div class="mt-2 text-red-500 text-sm">Vui lòng chọn khách hàng</div> @enderror
        @error('pTk_Co') <div class="mt-2 text-red-500 text-sm">Vui lòng chọn tài khoản có</div> @enderror
    </div>

    <datalist id="ArDmKh-suggestions">
        @foreach ($arDmKhs as $arDmKh)
            <option value="{{ $arDmKh->ma_kh }}">{{ $arDmKh->ten_kh }}</option>
        @endforeach
    </datalist>
</div>
