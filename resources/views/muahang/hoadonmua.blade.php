<div class="hoadonmua-container w-full">
    <x-head-title>{{ 'Hóa đơn mua hàng - Mua hàng' }}</x-head-title>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ 'Hóa đơn mua hàng' }}
                </h2>
                <p>{{ 'Danh sách hóa đơn mua hàng (PO3)' }}</p>
            </div>
            <a href="{{ route('muahang.hoadonmua.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                + Thêm mới
            </a>
        </div>
    </x-slot>

    {{-- Bộ lọc --}}
    <div class="bg-white p-4 rounded-lg shadow mb-4 border border-gray-100">
        <form wire:submit.prevent="loadData" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tìm kiếm</label>
                    <input type="text" wire:model.defer="pSearch"
                        placeholder="Số HĐ, số CT, mã NCC..."
                        class="w-full border-gray-300 rounded-md shadow-sm text-xs focus:border-blue-500 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Từ ngày</label>
                    <input type="date" wire:model.defer="pNgayCt1"
                        class="w-full border-gray-300 rounded-md shadow-sm text-xs focus:border-blue-500 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Đến ngày</label>
                    <input type="date" wire:model.defer="pNgayCt2"
                        class="w-full border-gray-300 rounded-md shadow-sm text-xs focus:border-blue-500 focus:ring-blue-500" />
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 border-t border-gray-100 pt-3">
                <button type="button" wire:click="resetFilters"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    Xóa lọc
                </button>
                <button type="submit"
                    class="inline-flex items-center px-6 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Lọc
                </button>
            </div>
        </form>
    </div>

    {{-- Bảng kết quả --}}
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full text-xs">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-2 py-2 text-left font-medium text-gray-600">STT</th>
                    <th class="px-2 py-2 text-left font-medium text-gray-600">Số CT</th>
                    <th class="px-2 py-2 text-left font-medium text-gray-600">Ngày CT</th>
                    <th class="px-2 py-2 text-left font-medium text-gray-600">Số HĐ</th>
                    <th class="px-2 py-2 text-left font-medium text-gray-600">Ngày HĐ</th>
                    <th class="px-2 py-2 text-left font-medium text-gray-600">Mã NCC</th>
                    <th class="px-2 py-2 text-left font-medium text-gray-600">Diễn giải</th>
                    <th class="px-2 py-2 text-right font-medium text-gray-600">Tổng tiền</th>
                    <th class="px-2 py-2 text-center font-medium text-gray-600">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($invoices ?? [] as $invoice)
                    <tr class="border-b hover:bg-sky-50" wire:key="inv-{{ $invoice->stt_rec }}">
                        <td class="px-2 py-1">{{ ($paginator->currentPage() - 1) * $paginator->perPage() + $loop->iteration }}</td>
                        <td class="px-2 py-1 font-mono">{{ $invoice->so_ct }}</td>
                        <td class="px-2 py-1">{{ optional($invoice->ngay_ct)->format('d/m/Y') }}</td>
                        <td class="px-2 py-1 font-mono">{{ $invoice->so_hd }}</td>
                        <td class="px-2 py-1">{{ optional($invoice->ngay_hd)->format('d/m/Y') }}</td>
                        <td class="px-2 py-1 font-mono">{{ $invoice->ma_kh }}</td>
                        <td class="px-2 py-1">{{ Str::limit($invoice->dien_giai, 50) }}</td>
                        <td class="px-2 py-1 text-right font-mono">{{ number_format($invoice->t_tt, 0, ',', '.') }}</td>
                        <td class="px-2 py-1 text-center">
                            <a href="{{ route('muahang.hoadonmua.edit', $invoice->stt_rec) }}"
                                class="text-blue-600 hover:text-blue-800 mr-2">Sửa</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-2 py-4 text-center text-gray-500">
                            Không có dữ liệu
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if ($paginator && $paginator->hasPages())
            <div class="px-4 py-2 border-t">
                {{ $paginator->links() }}
            </div>
        @endif
    </div>
</div>
