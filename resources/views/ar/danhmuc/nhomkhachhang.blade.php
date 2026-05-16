<div class="nhomkhachhang-container w-full">
    <x-head-title>{{ 'Nhóm khách hàng - AR' }}</x-head-title>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ 'Danh sách nhóm khách hàng' }}
                </h2>
                <p class="text-sm text-gray-500">Quản lý danh mục nhóm khách hàng (AR)</p>
            </div>
            <a
                href="{{ route('ar.nhomkhachhang.create') }}"
                class="rounded-md bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700"
            >
                + Thêm nhóm khách hàng
            </a>
        </div>
    </x-slot>

    {{-- Search Bar --}}
    <div class="mt-4 flex items-center gap-4">
        <div class="flex w-full max-w-md items-center">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Tìm theo mã, tên nhóm, ghi chú..."
                class="w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
            />
        </div>
        <span class="text-xs text-gray-500">
            {{ $nhomkhachhangs->total() }} kết quả
        </span>
    </div>

    {{-- Table --}}
    <div class="mt-4 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="border-b border-gray-200 px-3 py-2 text-left font-medium text-gray-500">#</th>
                    <th class="border-b border-gray-200 px-3 py-2 text-left font-medium text-gray-500">Mã nhóm</th>
                    <th class="border-b border-gray-200 px-3 py-2 text-left font-medium text-gray-500">Tên nhóm</th>
                    <th class="border-b border-gray-200 px-3 py-2 text-left font-medium text-gray-500">Ghi chú</th>
                    <th class="border-b border-gray-200 px-3 py-2 text-left font-medium text-gray-500">Trạng thái</th>
                    <th class="border-b border-gray-200 px-3 py-2 text-right font-medium text-gray-500">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($nhomkhachhangs as $index => $nh)
                    <tr class="hover:bg-sky-50" wire:key="nh-{{ $nh->ma_nhkh }}">
                        <td class="border-b border-gray-100 px-3 py-2 text-gray-500">
                            {{ $nhomkhachhangs->firstItem() + $index }}
                        </td>
                        <td class="border-b border-gray-100 px-3 py-2 font-mono text-xs">
                            {{ $nh->ma_nhkh }}
                        </td>
                        <td class="border-b border-gray-100 px-3 py-2">
                            <span class="font-medium text-gray-900">{{ $nh->ten_nhkh }}</span>
                        </td>
                        <td class="border-b border-gray-100 px-3 py-2 text-gray-600">
                            {{ Str::limit($nh->ghi_chu, 60) }}
                        </td>
                        <td class="border-b border-gray-100 px-3 py-2">
                            @if ($nh->ksd)
                                <span class="inline-flex rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">
                                    Hoạt động
                                </span>
                            @else
                                <span class="inline-flex rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800">
                                    Khóa
                                </span>
                            @endif
                        </td>
                        <td class="border-b border-gray-100 px-3 py-2 text-right">
                            <div class="flex justify-end gap-2">
                                <a
                                    href="{{ route('ar.nhomkhachhang.edit', $nh->ma_nhkh) }}"
                                    class="rounded bg-yellow-100 px-2 py-1 text-xs text-yellow-700 hover:bg-yellow-200"
                                >
                                    Sửa
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-3 py-8 text-center text-gray-500">
                            Không tìm thấy nhóm khách hàng nào.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $nhomkhachhangs->links() }}
    </div>
</div>
