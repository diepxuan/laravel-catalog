<div class="po-dmcp-container w-full">
    <x-head-title>{{ 'Chi phí mua hàng - Mua hàng' }}</x-head-title>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ 'Chi phí mua hàng' }}
            </h2>
            <p class="text-sm text-gray-600">MA_CP - PODMCP - menu 10.90.14</p>
        </div>
    </x-slot>

    <div class="mt-4 flex items-center gap-4">
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Tìm theo mã, tên chi phí..."
            class="w-full max-w-md rounded-md border-gray-300 py-2 pl-3 pr-10 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
        />
        <span class="text-xs text-gray-500">
            {{ $items->total() }} kết quả
        </span>
    </div>

    <div class="mt-4 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="border-b border-gray-200 px-3 py-2 text-left font-medium text-gray-500">#</th>
                    <th class="border-b border-gray-200 px-3 py-2 text-left font-medium text-gray-500">Mã chi phí</th>
                    <th class="border-b border-gray-200 px-3 py-2 text-left font-medium text-gray-500">Tên chi phí</th>
                    <th class="border-b border-gray-200 px-3 py-2 text-left font-medium text-gray-500">Phân bổ</th>
                    <th class="border-b border-gray-200 px-3 py-2 text-center font-medium text-gray-500">KSD</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $index => $item)
                    <tr class="hover:bg-sky-50" wire:key="po-dmcp-{{ $item->ma_cp }}">
                        <td class="border-b border-gray-100 px-3 py-2 text-gray-500">
                            {{ $items->firstItem() + $index }}
                        </td>
                        <td class="border-b border-gray-100 px-3 py-2 font-mono text-xs">{{ $item->ma_cp }}</td>
                        <td class="border-b border-gray-100 px-3 py-2 font-medium text-gray-900">{{ $item->ten_cp }}</td>
                        <td class="border-b border-gray-100 px-3 py-2 text-gray-600">{{ $item->tt_pb }}</td>
                        <td class="border-b border-gray-100 px-3 py-2 text-center">{{ $item->ksd ? 'x' : '' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-3 py-8 text-center text-gray-500">
                            Không tìm thấy chi phí mua hàng nào.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $items->links() }}
    </div>
</div>
