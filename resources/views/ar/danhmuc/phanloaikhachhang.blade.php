<div class="w-full">
    <x-head-title>{{ 'Phân loại khách hàng - AR' }}</x-head-title>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Danh sách phân loại khách hàng</h2>
                <p class="text-sm text-gray-500">Phân loại 3 cấp (PL1, PL2, PL3)</p>
            </div>
            <a href="{{ route('ar.phanloaikhachhang.create') }}" class="rounded-md bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700">+ Thêm</a>
        </div>
    </x-slot>
    <div class="mt-4 flex items-center gap-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Tìm theo mã, tên, ghi chú..." class="w-full max-w-md rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
        <select wire:model.live="filterLoai" class="rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="">Tất cả loại</option>
            <option value="1">PL1 - Chính</option>
            <option value="2">PL2 - Phụ</option>
            <option value="3">PL3 - Bổ sung</option>
        </select>
        <span class="text-xs text-gray-500">{{ $items->total() }} kết quả</span>
    </div>
    <div class="mt-4 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="border-b px-3 py-2 text-left text-gray-500">#</th>
                    <th class="border-b px-3 py-2 text-left text-gray-500">Mã PL</th>
                    <th class="border-b px-3 py-2 text-left text-gray-500">Tên phân loại</th>
                    <th class="border-b px-3 py-2 text-left text-gray-500">Loại</th>
                    <th class="border-b px-3 py-2 text-left text-gray-500">Ghi chú</th>
                    <th class="border-b px-3 py-2 text-right text-gray-500">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $index => $it)
                    <tr class="hover:bg-sky-50" wire:key="pl-{{ $it->ma_plkh }}">
                        <td class="border-b px-3 py-2">{{ $items->firstItem() + $index }}</td>
                        <td class="border-b px-3 py-2 font-mono text-xs">{{ $it->ma_plkh }}</td>
                        <td class="border-b px-3 py-2 font-medium">{{ $it->ten_plkh }}</td>
                        <td class="border-b px-3 py-2">PL{{ $it->loai }}</td>
                        <td class="border-b px-3 py-2 text-gray-600">{{ Str::limit($it->ghi_chu, 60) }}</td>
                        <td class="border-b px-3 py-2 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('ar.phanloaikhachhang.edit', $it->ma_plkh) }}" class="rounded bg-yellow-100 px-2 py-1 text-xs text-yellow-700 hover:bg-yellow-200">Sửa</a>
                                <button wire:click="deleteItem('{{ $it->ma_plkh }}', {{ $it->loai }})" wire:confirm="Bạn có chắc chắn muốn xóa phân loại {{ $it->ma_plkh }}?" class="rounded bg-red-100 px-2 py-1 text-xs text-red-700 hover:bg-red-200">Xóa</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-3 py-8 text-center text-gray-500">Không tìm thấy.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $items->links() }}</div>
</div>
