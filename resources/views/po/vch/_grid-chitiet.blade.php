<div class="space-y-2">
    <div class="overflow-x-auto [&_input]:rounded-none [&_div_input]:text-xs">
    <table class="js-autofit min-w-max w-full text-xs">
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
                <th class="px-2 py-1 text-center font-medium text-gray-600" data-no-fit>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pChiTiet ?? [] as $i => $row)
                <tr wire:key="ct-{{ $i }}" class="border-b">
                    <td class="p-0 align-middle"><livewire:catalog::component.input-indmvt wire:model="pChiTiet.{{ $i }}.ma_vt" wire:key="ct-mavt-{{ $i }}" /></td>
                    <td class="p-0 align-middle"><input wire:model="pChiTiet.{{ $i }}.ten_vt" class="w-full rounded border border-gray-200 bg-gray-50 px-2 py-1 text-xs" readonly /></td>
                    <td class="p-0 align-middle"><input wire:model="pChiTiet.{{ $i }}.dvt" class="w-full rounded border border-gray-200 bg-gray-50 px-2 py-1 text-xs" readonly /></td>
                    <td class="p-0 align-middle"><livewire:catalog::component.input-indmkho wire:model="pChiTiet.{{ $i }}.ma_kho" wire:key="ct-makho-{{ $i }}" /></td>
                    <td class="p-0 align-middle"><input type="number" step="0.0001" wire:model.blur="pChiTiet.{{ $i }}.so_luong" wire:change="calculateChiTietRow({{ $i }})" class="w-full rounded border border-gray-200 px-2 py-1 text-right text-xs" /></td>
                    <td class="p-0 align-middle"><input type="number" step="0.01" wire:model.blur="pChiTiet.{{ $i }}.gia_nt0" wire:change="calculateChiTietRow({{ $i }})" class="w-full rounded border border-gray-200 px-2 py-1 text-right text-xs" /></td>
                    <td class="p-0 align-middle"><input wire:model="pChiTiet.{{ $i }}.tien_nt0" class="w-full rounded border border-gray-200 bg-gray-50 px-2 py-1 text-right text-xs" readonly /></td>
                    <td class="p-0 align-middle"><input type="number" step="0.01" wire:model.blur="pChiTiet.{{ $i }}.tl_ck" wire:change="calculateChiTietRow({{ $i }})" class="w-full rounded border border-gray-200 px-2 py-1 text-right text-xs" /></td>
                    <td class="p-0 align-middle"><input wire:model="pChiTiet.{{ $i }}.ck_nt" class="w-full rounded border border-gray-200 bg-gray-50 px-2 py-1 text-right text-xs" readonly /></td>
                    <td class="p-0 align-middle"><input type="number" step="0.01" wire:model.blur="pChiTiet.{{ $i }}.tien_cp_nt" wire:change="calculateChiTietRow({{ $i }})" class="w-full rounded border border-gray-200 px-2 py-1 text-right text-xs" /></td>
                    <td class="p-0 align-middle"><input type="number" step="0.01" wire:model.blur="pChiTiet.{{ $i }}.ts_gtgt" wire:change="calculateChiTietRow({{ $i }})" class="w-full rounded border border-gray-200 px-2 py-1 text-right text-xs" /></td>
                    <td class="p-0 align-middle"><input wire:model="pChiTiet.{{ $i }}.thue_gtgt_nt" class="w-full rounded border border-gray-200 bg-gray-50 px-2 py-1 text-right text-xs" readonly /></td>
                    <td class="p-0 align-middle text-center">
                        <button type="button" wire:click="removeChiTietRow({{ $i }})" class="rounded border border-red-200 px-2 py-1 text-xs text-red-600 hover:bg-red-50">Xóa</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="13" class="px-2 py-3 text-center text-xs text-gray-500">Chưa có dòng chi tiết</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    <div class="flex justify-end gap-2 pt-2">
        <button type="button" wire:click="addChiTietRow" class="rounded border border-gray-300 px-3 py-1 text-xs text-gray-700 hover:bg-gray-50">+ Thêm dòng</button>
    </div>
</div>
@once
    <script>
        (() => {
            if (window.__portalGridAutofitInstalled) return;
            window.__portalGridAutofitInstalled = true;

            const ctx = document.createElement('canvas').getContext('2d');

            // Chromium tra ve chuoi rong cho shorthand .font -> ghep tu longhand
            const fontOf = (el) => {
                const s = getComputedStyle(el);
                return `${s.fontWeight} ${s.fontSize} ${s.fontFamily}`;
            };

            const padBox = (el) => {
                const s = getComputedStyle(el);
                return parseFloat(s.paddingLeft) + parseFloat(s.paddingRight)
                    + parseFloat(s.borderLeftWidth) + parseFloat(s.borderRightWidth);
            };

            const fitTable = (table) => {
                const ths = [...table.querySelectorAll('thead th')];
                const firstTd = table.querySelector('tbody td');
                if (!firstTd || firstTd.colSpan > 1) return; // empty state

                const widths = [];
                ths.forEach((th, i) => {
                    ctx.font = fontOf(th);
                    widths[i] = Math.ceil(ctx.measureText(th.textContent.trim()).width + padBox(th));
                });

                table.querySelectorAll('tbody tr').forEach((tr) => {
                    [...tr.children].forEach((td, i) => {
                        const inp = td.querySelector('input');
                        if (!inp) {
                            // cot khong co input (nut thao tac): width theo phan tu ben trong
                            const el = td.firstElementChild;
                            if (el) widths[i] = Math.max(widths[i], Math.ceil(el.getBoundingClientRect().width));
                            return;
                        }
                        ctx.font = fontOf(inp);
                        const spinPad = inp.type === 'number' ? 14 : 0;
                        const w = Math.ceil(
                            ctx.measureText(String(inp.value ?? '')).width + padBox(inp) + spinPad
                        );
                        widths[i] = Math.max(widths[i], w);
                    });
                });

                const apply = (row) => [...row.children].forEach((cell, i) => {
                    if (widths[i] != null) cell.style.width = widths[i] + 'px';
                });
                apply(table.querySelector('thead tr'));
                table.querySelectorAll('tbody tr').forEach(apply);
            };

            const fitAll = () => document.querySelectorAll('table.js-autofit').forEach(fitTable);
            const run = () => requestAnimationFrame(fitAll);

            // handle debug/verify tu ben ngoai
            window.__gridFit = { run, fitAll };

            run();
            window.addEventListener('resize', run);
            document.addEventListener('livewire:navigated', run);

            // User go: fit ngay theo noi dung dang go
            const fromGridInput = (e) => !!(e.target && e.target.closest && e.target.closest('table.js-autofit'));
            document.addEventListener('input', (e) => { if (fromGridInput(e)) run(); });
            document.addEventListener('change', (e) => { if (fromGridInput(e)) run(); });

            // Bat moi thay doi gia tri tu server (recalc readonly, add/remove dong):
            // so signature gia tri dinh ky, khac thi fit — khong phu thuoc API noi bo cua Livewire.
            let lastSig = '';
            setInterval(() => {
                const inputs = document.querySelectorAll('table.js-autofit tbody input');
                const rows = document.querySelectorAll('table.js-autofit tbody tr');
                const sig = `${rows.length}\u0001${[...inputs].map((i) => i.value).join('\u0001')}`;
                if (sig !== lastSig) {
                    lastSig = sig;
                    run();
                }
            }, 400);
        })();
    </script>
@endonce
