<div class="space-y-2">
    <table class="min-w-max w-full text-xs">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-2 py-1 text-left font-medium text-gray-600">Mã VT</th>
                <th class="px-2 py-1 text-left font-medium text-gray-600">Tên VT</th>
                <th class="px-2 py-1 text-left font-medium text-gray-600">ĐVT</th>
                <th class="px-2 py-1 text-left font-medium text-gray-600">Mã kho</th>
                <th class="px-2 py-1 text-right font-medium text-gray-600">Số lượng</th>
                <th class="px-2 py-1 text-right font-medium text-gray-600">Đơn giá NT</th>
                <th class="px-2 py-1 text-right font-medium text-gray-600">Thành tiền NT</th>
                <th class="px-2 py-1 text-right font-medium text-gray-600">% CK</th>
                <th class="px-2 py-1 text-right font-medium text-gray-600">CK NT</th>
                <th class="px-2 py-1 text-right font-medium text-gray-600">Chi phí NT</th>
                <th class="px-2 py-1 text-right font-medium text-gray-600">% VAT</th>
                <th class="px-2 py-1 text-right font-medium text-gray-600">Thuế NT</th>
                <th class="px-2 py-1 text-center font-medium text-gray-600">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pChiTiet ?? [] as $i => $row)
                <tr wire:key="ct-{{ $i }}" class="border-b">
                    <td class="px-1 py-1"><livewire:catalog::component.input-indmvt wire:model="pChiTiet.{{ $i }}.ma_vt" wire:key="ct-mavt-{{ $i }}" /></td>
                    <td class="px-1 py-1"><input wire:model="pChiTiet.{{ $i }}.ten_vt" class="w-full rounded border border-gray-200 bg-gray-50 px-2 py-1 text-xs" readonly /></td>
                    <td class="px-1 py-1"><input wire:model="pChiTiet.{{ $i }}.dvt" class="w-full rounded border border-gray-200 bg-gray-50 px-2 py-1 text-xs" readonly /></td>
                    <td class="px-1 py-1"><livewire:catalog::component.input-indmkho wire:model="pChiTiet.{{ $i }}.ma_kho" wire:key="ct-makho-{{ $i }}" /></td>
                    <td class="px-1 py-1"><input type="number" step="0.0001" wire:model.blur="pChiTiet.{{ $i }}.so_luong" wire:change="calculateChiTietRow({{ $i }})" class="w-24 rounded border border-gray-200 px-2 py-1 text-right text-xs" /></td>
                    <td class="px-1 py-1"><input type="number" step="0.01" wire:model.blur="pChiTiet.{{ $i }}.gia_nt0" wire:change="calculateChiTietRow({{ $i }})" class="w-28 rounded border border-gray-200 px-2 py-1 text-right text-xs" /></td>
                    <td class="px-1 py-1"><input wire:model="pChiTiet.{{ $i }}.tien_nt0" class="w-28 rounded border border-gray-200 bg-gray-50 px-2 py-1 text-right text-xs" readonly /></td>
                    <td class="px-1 py-1"><input type="number" step="0.01" wire:model.blur="pChiTiet.{{ $i }}.tl_ck" wire:change="calculateChiTietRow({{ $i }})" class="w-16 rounded border border-gray-200 px-2 py-1 text-right text-xs" /></td>
                    <td class="px-1 py-1"><input wire:model="pChiTiet.{{ $i }}.ck_nt" class="w-24 rounded border border-gray-200 bg-gray-50 px-2 py-1 text-right text-xs" readonly /></td>
                    <td class="px-1 py-1"><input type="number" step="0.01" wire:model.blur="pChiTiet.{{ $i }}.tien_cp_nt" wire:change="calculateChiTietRow({{ $i }})" class="w-24 rounded border border-gray-200 px-2 py-1 text-right text-xs" /></td>
                    <td class="px-1 py-1"><input type="number" step="0.01" wire:model.blur="pChiTiet.{{ $i }}.ts_gtgt" wire:change="calculateChiTietRow({{ $i }})" class="w-16 rounded border border-gray-200 px-2 py-1 text-right text-xs" /></td>
                    <td class="px-1 py-1"><input wire:model="pChiTiet.{{ $i }}.thue_gtgt_nt" class="w-24 rounded border border-gray-200 bg-gray-50 px-2 py-1 text-right text-xs" readonly /></td>
                    <td class="px-1 py-1 text-center">
                        <button type="button" wire:click="removeChiTietRow({{ $i }})" class="rounded border border-red-200 px-2 py-1 text-xs text-red-600 hover:bg-red-50">Xóa</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="13" class="px-2 py-3 text-center text-xs text-gray-500">Chưa có dòng chi tiết</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="flex justify-end gap-2 pt-2">
        <button type="button" wire:click="addChiTietRow" class="rounded border border-gray-300 px-3 py-1 text-xs text-gray-700 hover:bg-gray-50">+ Thêm dòng</button>
    </div>
</div>
