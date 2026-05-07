<div class="khachhang-container w-full">
    <x-head-title>{{ 'Khách hàng - Bán hàng' }}</x-head-title>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ 'Danh sách khách hàng' }}
                </h2>
                <p class="text-sm text-gray-500">Quản lý danh mục khách hàng</p>
            </div>
            <a
                href="{{ route('ar.khachhang.create') }}"
                class="rounded-md bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700"
            >
                + Thêm khách hàng
            </a>
        </div>
    </x-slot>

    {{-- Search Bar --}}
    <div class="mt-4 flex items-center gap-4">
        <div class="flex w-full max-w-md items-center">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Tìm theo mã, tên, địa chỉ, điện thoại..."
                class="w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
            />
            @if ($search)
                <button
                    wire:click="$set('search', '')"
                    class="absolute right-3 text-gray-400 hover:text-gray-600"
                >
                    ✕
                </button>
            @endif
        </div>
        <span class="text-xs text-gray-500">
            {{ $khachhangs->total() }} kết quả
        </span>
    </div>

    {{-- Table --}}
    <div class="mt-4 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="border-b border-gray-200 px-3 py-2 text-left font-medium text-gray-500">#</th>
                    <th class="border-b border-gray-200 px-3 py-2 text-left font-medium text-gray-500">Mã KH</th>
                    <th class="border-b border-gray-200 px-3 py-2 text-left font-medium text-gray-500">Tên khách hàng</th>
                    <th class="border-b border-gray-200 px-3 py-2 text-left font-medium text-gray-500">Địa chỉ</th>
                    <th class="border-b border-gray-200 px-3 py-2 text-left font-medium text-gray-500">Điện thoại</th>
                    <th class="border-b border-gray-200 px-3 py-2 text-left font-medium text-gray-500">Người GD</th>
                    <th class="border-b border-gray-200 px-3 py-2 text-left font-medium text-gray-500">Nhóm KH</th>
                    <th class="border-b border-gray-200 px-3 py-2 text-right font-medium text-gray-500">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($khachhangs as $index => $kh)
                    <tr class="hover:bg-sky-50" wire:key="kh-{{ $kh->ma_kh }}">
                        <td class="border-b border-gray-100 px-3 py-2 text-gray-500">
                            {{ $khachhangs->firstItem() + $index }}
                        </td>
                        <td class="border-b border-gray-100 px-3 py-2 font-mono text-xs">
                            {{ $kh->ma_kh }}
                        </td>
                        <td class="border-b border-gray-100 px-3 py-2">
                            <span class="font-medium text-gray-900">{{ $kh->ten_kh }}</span>
                        </td>
                        <td class="border-b border-gray-100 px-3 py-2 text-gray-600">
                            {{ Str::limit($kh->dia_chi, 40) }}
                        </td>
                        <td class="border-b border-gray-100 px-3 py-2 text-gray-600">
                            {{ $kh->tel }}
                        </td>
                        <td class="border-b border-gray-100 px-3 py-2 text-gray-600">
                            {{ $kh->nguoi_gd }}
                        </td>
                        <td class="border-b border-gray-100 px-3 py-2 text-gray-600">
                            {{ $kh->ma_nhkh }}
                        </td>
                        <td class="border-b border-gray-100 px-3 py-2 text-right">
                            <div class="flex justify-end gap-2">
                                <a
                                    href="{{ route('ar.khachhang.edit', $kh->ma_kh) }}"
                                    class="rounded bg-yellow-100 px-2 py-1 text-xs text-yellow-700 hover:bg-yellow-200"
                                >
                                    Sửa
                                </a>
                                <button
                                    wire:click="deleteKhachHang('{{ $kh->ma_kh }}')"
                                    wire:confirm="Bạn có chắc chắn muốn xóa khách hàng {{ $kh->ma_kh }}?"
                                    class="rounded bg-red-100 px-2 py-1 text-xs text-red-700 hover:bg-red-200"
                                >
                                    Xóa
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-3 py-8 text-center text-gray-500">
                            Không tìm thấy khách hàng nào.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $khachhangs->links() }}
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('success', (event) => {
            window.dispatchEvent(new CustomEvent('toast-success', { detail: { message: event.message } }));
        });
        Livewire.on('error', (event) => {
            window.dispatchEvent(new CustomEvent('toast-error', { detail: { message: event.message } }));
        });
    });
</script>
@endpush
