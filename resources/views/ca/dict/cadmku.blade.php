<div class="w-full">
    <x-head-title>{{ 'Danh mục khế ước' }}</x-head-title>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div><h2 class="text-xl font-semibold text-gray-800">Danh mục khế ước</h2><p class="text-sm text-gray-500">CADMKU - quản lý khế ước vay</p></div>
            <a href="{{ route('ca.kheuoc.create') }}" class="rounded-md bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700">+ Thêm</a>
        </div>
    </x-slot>
    <div class="mt-4 flex items-center gap-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Tìm theo mã, tên, số khế ước..." class="w-full max-w-md rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
        <span class="text-xs text-gray-500">{{ $items->total() }} kết quả</span>
    </div>
    <div class="mt-4 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr><th class="border-b px-3 py-2 text-left">#</th><th class="border-b px-3 py-2 text-left">Mã KU</th><th class="border-b px-3 py-2 text-left">Tên khế ước</th><th class="border-b px-3 py-2 text-left">Số KU</th><th class="border-b px-3 py-2 text-left">Ngày vay</th><th class="border-b px-3 py-2 text-right">Tiền</th><th class="border-b px-3 py-2 text-right">Thao tác</th></tr></thead>
            <tbody>
                @forelse ($items as $index => $it)
                    <tr class="hover:bg-sky-50" wire:key="ku-{{ $it->ma_ku }}">
                        <td class="border-b px-3 py-2">{{ $items->firstItem() + $index }}</td>
                        <td class="border-b px-3 py-2 font-mono text-xs">{{ $it->ma_ku }}</td>
                        <td class="border-b px-3 py-2 font-medium">{{ $it->ten_ku }}</td>
                        <td class="border-b px-3 py-2">{{ $it->so_ku }}</td>
                        <td class="border-b px-3 py-2">{{ $it->ngay_vay }}</td>
                        <td class="border-b px-3 py-2 text-right">{{ number_format($it->tien, 0, ',', '.') }}</td>
                        <td class="border-b px-3 py-2 text-right"><div class="flex justify-end gap-2"><a href="{{ route('ca.kheuoc.edit', $it->ma_ku) }}" class="rounded bg-yellow-100 px-2 py-1 text-xs text-yellow-700 hover:bg-yellow-200">Sửa</a><button wire:click="deleteItem('{{ $it->ma_ku }}')" wire:confirm="Bạn có chắc chắn muốn xóa khế ước {{ $it->ma_ku }}?" class="rounded bg-red-100 px-2 py-1 text-xs text-red-700 hover:bg-red-200">Xóa</button></div></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-3 py-8 text-center text-gray-500">Không tìm thấy khế ước.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $items->links() }}</div>
</div>
