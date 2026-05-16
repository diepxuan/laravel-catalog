<div class="w-full">
    <x-head-title>{{ 'Định mức chi tiết CODD1' }}</x-head-title>
    <x-slot name="header"><div><h2 class="text-xl font-semibold text-gray-800">Định mức chi tiết</h2><p class="text-sm text-gray-500">CODD1 - read-only theo năm/tháng</p></div></x-slot>
    <div class="mt-4 grid gap-3 md:grid-cols-4">
        <input type="number" wire:model.live.debounce.300ms="nam" class="rounded-md border-gray-300 text-sm" placeholder="Năm" />
        <input type="number" min="1" max="12" wire:model.live.debounce.300ms="thang" class="rounded-md border-gray-300 text-sm" placeholder="Tháng" />
        <input wire:model.live.debounce.300ms="maSpct" class="rounded-md border-gray-300 text-sm" placeholder="Mã SPCT" />
        <input wire:model.live.debounce.300ms="tk" class="rounded-md border-gray-300 text-sm" placeholder="Tài khoản" />
    </div>
    <div class="mt-4 overflow-hidden rounded-lg border bg-white shadow-sm">
        <table class="w-full text-sm"><thead class="bg-gray-50"><tr><th class="border-b px-3 py-2 text-left">Mã SPCT</th><th class="border-b px-3 py-2 text-left">TK</th><th class="border-b px-3 py-2 text-right">SL DD CK</th><th class="border-b px-3 py-2 text-right">GT DD CK</th></tr></thead><tbody>
            @forelse ($items as $row)
                <tr><td class="border-b px-3 py-2 font-mono text-xs">{{ $row->ma_spct }}</td><td class="border-b px-3 py-2 font-mono text-xs">{{ $row->tk }}</td><td class="border-b px-3 py-2 text-right">{{ number_format($row->sl_dd_ck, 2, ',', '.') }}</td><td class="border-b px-3 py-2 text-right">{{ number_format($row->gt_dd_ck, 0, ',', '.') }}</td></tr>
            @empty <tr><td colspan="4" class="px-3 py-8 text-center text-gray-500">Không có dữ liệu.</td></tr>@endforelse
        </tbody></table>
    </div>
    <div class="mt-4">{{ $items->links() }}</div>
</div>
