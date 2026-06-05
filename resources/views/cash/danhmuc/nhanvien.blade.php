<div class="phieuthu-container w-full">
    <x-head-title>{{ 'Nhân viên - Tiền mặt' }}</x-head-title>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ 'Danh mục nhân viên' }}</h2>
                <p class="text-sm text-gray-500">Theo Simba menu 04.90.05 / ARDMKH / frmARDMKH</p>
            </div>
            <a href="{{ route('ca.nhanvien.create') }}" class="rounded-md bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700">+ Thêm nhân viên</a>
        </div>
    </x-slot>

    <div class="mt-4 flex items-center gap-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Tìm theo mã, tên, địa chỉ, điện thoại, mã số thuế..." class="w-full max-w-md rounded-md border-gray-300 py-2 pl-3 pr-10 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
        <span class="text-xs text-gray-500">{{ $arDmKhs->total() }} kết quả</span>
    </div>

    <div class="mt-4 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr><th class="border-b px-3 py-2 text-left font-medium text-gray-500">#</th><th class="border-b px-3 py-2 text-left font-medium text-gray-500">Mã NV</th><th class="border-b px-3 py-2 text-left font-medium text-gray-500">Tên nhân viên</th><th class="border-b px-3 py-2 text-left font-medium text-gray-500">Địa chỉ</th><th class="border-b px-3 py-2 text-left font-medium text-gray-500">Điện thoại</th><th class="border-b px-3 py-2 text-left font-medium text-gray-500">Người GD</th><th class="border-b px-3 py-2 text-right font-medium text-gray-500">Thao tác</th></tr></thead>
            <tbody>
                @forelse ($arDmKhs as $index => $arDmKh)
                    <tr class="hover:bg-sky-50" wire:key="nv-{{ $arDmKh->ma_kh }}">
                        <td class="border-b border-gray-100 px-3 py-2 text-gray-500">{{ $arDmKhs->firstItem() + $index }}</td>
                        <td class="border-b border-gray-100 px-3 py-2 font-mono text-xs">{{ $arDmKh->ma_kh }}</td>
                        <td class="border-b border-gray-100 px-3 py-2 font-medium text-gray-900">{{ $arDmKh->ten_kh }}</td>
                        <td class="border-b border-gray-100 px-3 py-2 text-gray-600">{{ Str::limit($arDmKh->dia_chi, 40) }}</td>
                        <td class="border-b border-gray-100 px-3 py-2 text-gray-600">{{ $arDmKh->tel }}</td>
                        <td class="border-b border-gray-100 px-3 py-2 text-gray-600">{{ $arDmKh->nguoi_gd }}</td>
                        <td class="border-b border-gray-100 px-3 py-2 text-right"><div class="flex justify-end gap-2"><a href="{{ route('ca.nhanvien.edit', $arDmKh->ma_kh) }}" class="rounded bg-yellow-100 px-2 py-1 text-xs text-yellow-700 hover:bg-yellow-200">Sửa</a><button wire:click="deleteDoiTuong('{{ $arDmKh->ma_kh }}')" wire:confirm="Bạn có chắc chắn muốn xóa nhân viên {{ $arDmKh->ma_kh }}?" class="rounded bg-red-100 px-2 py-1 text-xs text-red-700 hover:bg-red-200">Xóa</button></div></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-3 py-8 text-center text-gray-500">Không tìm thấy nhân viên nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $arDmKhs->links() }}</div>
</div>
