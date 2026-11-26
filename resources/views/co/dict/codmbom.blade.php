<div class="w-full">
    <x-head-title>{{ 'Định mức nguyên vật liệu BOM' }}</x-head-title>
    <x-slot name="header"><div><h2 class="text-xl font-semibold text-gray-800">Định mức nguyên vật liệu</h2><p class="text-sm text-gray-500">CODMBOM - read-only theo source Simba</p></div></x-slot>
    <div class="mt-4"><input wire:model.live.debounce.300ms="search" class="w-full max-w-md rounded-md border-gray-300 text-sm" placeholder="Tìm mã/tên sản phẩm..." /></div>
    <div class="mt-4 grid gap-4 lg:grid-cols-2">
        <div class="overflow-hidden rounded-lg border bg-white shadow-sm">
            <table class="w-full text-sm"><thead class="bg-gray-50"><tr><th class="border-b px-3 py-2 text-left">Mã SPCT</th><th class="border-b px-3 py-2 text-left">Tên</th><th class="border-b px-3 py-2 text-right">ĐG lương</th></tr></thead><tbody>
                @forelse ($headers as $row)
                    <tr wire:click="selectBom('{{ $row->ma_spct }}')" class="cursor-pointer hover:bg-sky-50 {{ $selectedMaSpct === $row->ma_spct ? 'bg-sky-100' : '' }}"><td class="border-b px-3 py-2 font-mono text-xs">{{ $row->ma_spct }}</td><td class="border-b px-3 py-2">{{ $row->ten_spct }}</td><td class="border-b px-3 py-2 text-right">{{ number_format($row->don_gia_luong, 0, ',', '.') }}</td></tr>
                @empty <tr><td colspan="3" class="px-3 py-8 text-center text-gray-500">Không có BOM.</td></tr>@endforelse
            </tbody></table><div class="p-3">{{ $headers->links() }}</div>
        </div>
        <div class="overflow-hidden rounded-lg border bg-white shadow-sm">
            <div class="border-b bg-gray-50 px-3 py-2 text-sm font-medium">Chi tiết vật tư {{ $selectedMaSpct }}</div>
            <table class="w-full text-sm"><thead><tr><th class="border-b px-3 py-2 text-left">Mã VT</th><th class="border-b px-3 py-2 text-left">Tên VT</th><th class="border-b px-3 py-2 text-right">SL</th><th class="border-b px-3 py-2 text-center">Chính</th></tr></thead><tbody>
                @forelse ($details as $row)
                    <tr><td class="border-b px-3 py-2 font-mono text-xs">{{ $row->ma_vt }}</td><td class="border-b px-3 py-2">{{ $row->ten_vt }}</td><td class="border-b px-3 py-2 text-right">{{ number_format($row->so_luong, 2, ',', '.') }}</td><td class="border-b px-3 py-2 text-center">{{ $row->vt_chinh ? 'Có' : '' }}</td></tr>
                @empty <tr><td colspan="4" class="px-3 py-8 text-center text-gray-500">Chọn BOM để xem chi tiết.</td></tr>@endforelse
            </tbody></table>
        </div>
    </div>
</div>
