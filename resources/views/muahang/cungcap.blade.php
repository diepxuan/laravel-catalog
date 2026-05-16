<div class="phieuthu-container w-full">
    <x-head-title>{{ 'Nhà cung cấp - Mua hàng' }}</x-head-title>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ 'Nhà cung cấp' }}
        </h2>
        <p>{{ 'Danh sách nhà cung cấp' }}</p>
    </x-slot>

    <div class="mt-4 flex items-center gap-4">
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Tìm theo mã, tên, địa chỉ, điện thoại, mã số thuế..."
            class="w-full max-w-md rounded-md border-gray-300 py-2 pl-3 pr-10 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
        />
        <span class="text-xs text-gray-500">
            {{ $arDmKhs->total() }} kết quả
        </span>
    </div>

    <div class="mt-4 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="border-b border-gray-200 px-3 py-2 text-left font-medium text-gray-500">#</th>
                    <th class="border-b border-gray-200 px-3 py-2 text-left font-medium text-gray-500">Mã NCC</th>
                    <th class="border-b border-gray-200 px-3 py-2 text-left font-medium text-gray-500">Tên nhà cung cấp</th>
                    <th class="border-b border-gray-200 px-3 py-2 text-left font-medium text-gray-500">Địa chỉ</th>
                    <th class="border-b border-gray-200 px-3 py-2 text-left font-medium text-gray-500">Điện thoại</th>
                    <th class="border-b border-gray-200 px-3 py-2 text-left font-medium text-gray-500">Người GD</th>
                    <th class="border-b border-gray-200 px-3 py-2 text-left font-medium text-gray-500">Hình thức TT</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($arDmKhs as $index => $arDmKh)
                    <tr class="hover:bg-sky-50" wire:key="ncc-{{ $arDmKh->ma_kh }}">
                        <td class="border-b border-gray-100 px-3 py-2 text-gray-500">
                            {{ $arDmKhs->firstItem() + $index }}
                        </td>
                        <td class="border-b border-gray-100 px-3 py-2 font-mono text-xs">{{ $arDmKh->ma_kh }}</td>
                        <td class="border-b border-gray-100 px-3 py-2 font-medium text-gray-900">{{ $arDmKh->ten_kh }}</td>
                        <td class="border-b border-gray-100 px-3 py-2 text-gray-600">{{ Str::limit($arDmKh->dia_chi, 40) }}</td>
                        <td class="border-b border-gray-100 px-3 py-2 text-gray-600">{{ $arDmKh->tel }}</td>
                        <td class="border-b border-gray-100 px-3 py-2 text-gray-600">{{ $arDmKh->nguoi_gd }}</td>
                        <td class="border-b border-gray-100 px-3 py-2 text-gray-600">{{ $arDmKh->ma_httt_po }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-3 py-8 text-center text-gray-500">
                            Không tìm thấy nhà cung cấp nào.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $arDmKhs->links() }}
    </div>

</div>
