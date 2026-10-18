<div class="space-y-2">
    <div class="overflow-x-auto [&_input]:rounded-none [&_div_input]:text-xs">
    <table class="js-autofit min-w-max w-full text-xs">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-2 py-1 text-left font-medium text-gray-600">Mã chi phí</th>
                <th class="px-2 py-1 text-left font-medium text-gray-600">Tên chi phí</th>
                <th class="px-2 py-1 text-center font-medium text-gray-600">PP</th>
                <th class="px-2 py-1 text-right font-medium text-gray-600">Tiền CP NT</th>
                <th class="px-2 py-1 text-right font-medium text-gray-600">% VAT</th>
                <th class="px-2 py-1 text-right font-medium text-gray-600">Thuế NT</th>
                <th class="px-2 py-1 text-right font-medium text-gray-600">Tổng NT</th>
                <th class="px-2 py-1 text-left font-medium text-gray-600">Mã BP</th>
                <th class="px-2 py-1 text-left font-medium text-gray-600">Mã phí</th>
                <th class="px-2 py-1 text-left font-medium text-gray-600">Mã SPCT</th>
                <th class="px-2 py-1 text-left font-medium text-gray-600">Mã lô</th>
                <th class="px-2 py-1 text-center font-medium text-gray-600" data-no-fit>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pChiPhi ?? [] as $i => $row)
                <tr wire:key="cp-{{ $i }}" class="border-b">
                    <td class="p-0 align-middle"><livewire:catalog::component.input-chiphi wire:model="pChiPhi.{{ $i }}.ma_cp" wire:key="cp-macp-{{ $i }}" /></td>
                    <td class="p-0 align-middle"><input wire:model="pChiPhi.{{ $i }}.ten_cp" class="w-full rounded border border-gray-200 bg-gray-50 px-2 py-1 text-xs" readonly /></td>
                    <td class="p-0 align-middle"><input wire:model="pChiPhi.{{ $i }}.tt_pb" class="w-full rounded border border-gray-200 px-2 py-1 text-center text-xs" /></td>
                    <td class="p-0 align-middle"><input type="number" step="0.01" wire:model.blur="pChiPhi.{{ $i }}.tien_cp_nt" wire:change="calculateChiPhiRow({{ $i }})" class="w-full rounded border border-gray-200 px-2 py-1 text-right text-xs" /></td>
                    <td class="p-0 align-middle"><input type="number" step="0.01" wire:model.blur="pChiPhi.{{ $i }}.ts_gtgt" wire:change="calculateChiPhiRow({{ $i }})" class="w-full rounded border border-gray-200 px-2 py-1 text-right text-xs" /></td>
                    <td class="p-0 align-middle"><input wire:model="pChiPhi.{{ $i }}.thue_gtgt_nt" class="w-full rounded border border-gray-200 bg-gray-50 px-2 py-1 text-right text-xs" readonly /></td>
                    <td class="p-0 align-middle"><input wire:model="pChiPhi.{{ $i }}.tt_nt" class="w-full rounded border border-gray-200 bg-gray-50 px-2 py-1 text-right text-xs" readonly /></td>
                    <td class="p-0 align-middle"><input wire:model="pChiPhi.{{ $i }}.ma_bp" class="w-full rounded border border-gray-200 px-2 py-1 text-xs" /></td>
                    <td class="p-0 align-middle"><input wire:model="pChiPhi.{{ $i }}.ma_phi" class="w-full rounded border border-gray-200 px-2 py-1 text-xs" /></td>
                    <td class="p-0 align-middle"><input wire:model="pChiPhi.{{ $i }}.ma_spct" class="w-full rounded border border-gray-200 px-2 py-1 text-xs" /></td>
                    <td class="p-0 align-middle"><input wire:model="pChiPhi.{{ $i }}.ma_lo" class="w-full rounded border border-gray-200 px-2 py-1 text-xs" /></td>
                    <td class="p-0 align-middle text-center">
                        <button type="button" wire:click="removeChiPhiRow({{ $i }})" class="rounded border border-red-200 px-2 py-1 text-xs text-red-600 hover:bg-red-50">Xóa</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="12" class="px-2 py-3 text-center text-xs text-gray-500">Chưa có dòng chi phí</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    <div class="flex justify-end gap-2 pt-2">
        <button type="button" wire:click="addChiPhiRow" class="rounded border border-gray-300 px-3 py-1 text-xs text-gray-700 hover:bg-gray-50">+ Thêm dòng chi phí</button>
    </div>
</div>
