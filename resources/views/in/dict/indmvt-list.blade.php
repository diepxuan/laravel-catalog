<div class="space-y-3">
    @if ($errorMessage)
        <div class="rounded-md border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
            {{ $errorMessage }}
        </div>
    @endif

    @if ($statusMessage)
        <div class="rounded-md border border-green-200 bg-green-50 px-3 py-2 text-sm text-green-700">
            {{ $statusMessage }}
        </div>
    @endif

    {{-- Thanh tìm kiếm + nút Thêm --}}
    <div class="flex flex-wrap items-center gap-3">
        <div class="relative w-full max-w-xl">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Tìm theo mã, tên, nhóm, ĐVT, tài khoản vật tư..."
                class="w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
            />
            @if ($search)
                <button
                    type="button"
                    wire:click="$set('search', '')"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 hover:text-gray-600"
                >
                    Xóa
                </button>
            @endif
        </div>
        <span class="text-xs text-gray-500">{{ count($displayRows) }} / {{ count($rows) }} vật tư</span>
        <button
            type="button"
            wire:click="openCreate"
            class="rounded-md bg-blue-600 px-3 py-2 text-sm text-white hover:bg-blue-700"
        >
            Thêm vật tư
        </button>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
    @if ($loadError)
        <div class="border-b border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-700">
            Không nạp được dữ liệu Simba: {{ $loadError }}
        </div>
    @endif
    <div class="max-h-[calc(100vh-280px)] overflow-y-auto">
        <table class="min-w-full text-left text-xs">
            <thead class="sticky top-0 z-10 bg-gray-50 text-gray-500">
                <tr>
                    <th class="border-b border-gray-200 px-2 py-2 font-medium">#</th>
                    <th class="border-b border-gray-200 px-2 py-2 font-medium">Mã VT</th>
                    <th class="border-b border-gray-200 px-2 py-2 font-medium">Tên vật tư</th>
                    <th class="border-b border-gray-200 px-2 py-2 font-medium">ĐVT</th>
                    <th class="border-b border-gray-200 px-2 py-2 font-medium">Nhóm</th>
                    <th class="border-b border-gray-200 px-2 py-2 font-medium">Kho</th>
                    <th class="border-b border-gray-200 px-2 py-2 font-medium">Vị trí</th>
                    <th class="border-b border-gray-200 px-2 py-2 font-medium">TK VT</th>
                    <th class="border-b border-gray-200 px-2 py-2 font-medium">TK DT</th>
                    <th class="border-b border-gray-200 px-2 py-2 font-medium">TK GV</th>
                    <th class="border-b border-gray-200 px-2 py-2 text-center font-medium">Tồn</th>
                    <th class="border-b border-gray-200 px-2 py-2 font-medium">Loại</th>
                    <th class="border-b border-gray-200 px-2 py-2 font-medium">Giá tồn</th>
                    <th class="border-b border-gray-200 px-2 py-2 text-center font-medium">KSD</th>
                    <th class="border-b border-gray-200 px-2 py-2 text-right font-medium">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($displayRows as $row)
                    @php($maVt = (string) ($row['ma_vt'] ?? ''))
                    <tr
                        wire:key="indmvt-list-{{ $maVt }}"
                        wire:click="selectItem(@js($maVt))"
                        class="cursor-pointer hover:bg-sky-50 {{ $selectedMaVt === $maVt ? 'bg-blue-50' : '' }}"
                    >
                        <td class="px-2 py-2 text-right text-gray-400">{{ $loop->iteration }}</td>
                        <td class="whitespace-nowrap px-2 py-2 font-mono font-semibold text-gray-900">{{ $maVt }}</td>
                        <td class="min-w-64 px-2 py-2 text-gray-800">{{ $row['ten_vt'] ?? '' }}</td>
                        <td class="whitespace-nowrap px-2 py-2 font-mono">{{ $row['dvt'] ?? '' }}</td>
                        <td class="whitespace-nowrap px-2 py-2 font-mono">{{ $row['ma_nhvt'] ?? '' }}</td>
                        <td class="whitespace-nowrap px-2 py-2 font-mono">{{ $row['ma_kho'] ?? '' }}</td>
                        <td class="whitespace-nowrap px-2 py-2 font-mono">{{ $row['ma_vitri'] ?? '' }}</td>
                        <td class="whitespace-nowrap px-2 py-2 font-mono">{{ $row['tk_vt'] ?? '' }}</td>
                        <td class="whitespace-nowrap px-2 py-2 font-mono">{{ $row['tk_dt'] ?? '' }}</td>
                        <td class="whitespace-nowrap px-2 py-2 font-mono">{{ $row['tk_gv'] ?? '' }}</td>
                        <td class="px-2 py-2 text-center">{{ (int) ($row['ton_kho'] ?? 0) ? 'Có' : 'Không' }}</td>
                        <td class="whitespace-nowrap px-2 py-2 font-mono">{{ $row['loai'] ?? '' }}</td>
                        <td class="whitespace-nowrap px-2 py-2 font-mono">{{ $row['gia_ton'] ?? '' }}</td>
                        <td class="px-2 py-2 text-center">{{ (int) ($row['ksd'] ?? 0) ? 'Có' : '' }}</td>
                        <td class="whitespace-nowrap px-2 py-2 text-right" wire:click.stop>
                            @if ($deleteMaVt === $maVt)
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" wire:click="deleteItem" class="rounded bg-red-600 px-2 py-1 text-xs text-white hover:bg-red-700">Xác nhận</button>
                                    <button type="button" wire:click="cancelDelete" class="rounded bg-gray-100 px-2 py-1 text-xs text-gray-700 hover:bg-gray-200">Hủy</button>
                                </div>
                            @else
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" wire:click="openEdit(@js($maVt))" class="rounded bg-yellow-100 px-2 py-1 text-xs text-yellow-700 hover:bg-yellow-200">Sửa</button>
                                    <button type="button" wire:click="openRename(@js($maVt))" class="rounded bg-indigo-100 px-2 py-1 text-xs text-indigo-700 hover:bg-indigo-200">Đổi mã</button>
                                    <button type="button" wire:click="confirmDelete(@js($maVt))" class="rounded bg-red-100 px-2 py-1 text-xs text-red-700 hover:bg-red-200">Xóa</button>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="15" class="px-3 py-8 text-center text-sm text-gray-500">Không có vật tư phù hợp.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    </div>
</div>
