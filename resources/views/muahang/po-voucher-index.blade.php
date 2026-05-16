<div class="po-voucher-container w-full">
    <x-head-title>{{ $metadata['title'] }} - Mua hàng</x-head-title>

    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ $metadata['title'] }}
                </h2>
                <p class="text-sm text-gray-600">
                    {{ $voucherCode }} - {{ $metadata['ph'] }} / {{ $metadata['ct'] }} - menu {{ $metadata['sys_menuid'] ?? $metadata['menuid'] }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="bg-white p-4 rounded-lg shadow mb-4 border border-gray-100">
        <form wire:submit.prevent="loadData" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tìm kiếm</label>
                    <input type="text" wire:model.defer="pSearch"
                        placeholder="Số CT, diễn giải..."
                        class="w-full border-gray-300 rounded-md shadow-sm text-xs focus:border-blue-500 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Kỳ báo cáo</label>
                    <div class="w-full">
                        @livewire('catalog::component.timer')
                    </div>
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

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full text-xs">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-2 py-2 text-left font-medium text-gray-600">STT</th>
                    <th class="px-2 py-2 text-left font-medium text-gray-600">Số CT</th>
                    <th class="px-2 py-2 text-left font-medium text-gray-600">Ngày CT</th>
                    <th class="px-2 py-2 text-left font-medium text-gray-600">Mã NCC</th>
                    <th class="px-2 py-2 text-left font-medium text-gray-600">Diễn giải</th>
                    <th class="px-2 py-2 text-right font-medium text-gray-600">Tổng tiền</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($vouchers ?? [] as $voucher)
                    <tr class="border-b hover:bg-sky-50" wire:key="po-{{ $voucherCode }}-{{ $voucher->stt_rec }}">
                        <td class="px-2 py-1">{{ ($paginator->currentPage() - 1) * $paginator->perPage() + $loop->iteration }}</td>
                        <td class="px-2 py-1 font-mono">{{ $voucher->so_ct }}</td>
                        <td class="px-2 py-1">{{ optional($voucher->ngay_ct)->format('d/m/Y') }}</td>
                        <td class="px-2 py-1 font-mono">{{ $voucher->ma_kh }}</td>
                        <td class="px-2 py-1">{{ Str::limit($voucher->dien_giai, 70) }}</td>
                        <td class="px-2 py-1 text-right font-mono">{{ number_format((float) ($voucher->t_tt ?? 0), 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-2 py-4 text-center text-gray-500">
                            Không có dữ liệu
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($paginator && $paginator->hasPages())
            <div class="px-4 py-2 border-t">
                {{ $paginator->links() }}
            </div>
        @endif
    </div>
</div>
