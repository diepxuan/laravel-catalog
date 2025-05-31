<div class="phieuthu-container w-full">
    <x-head-title>{{ 'Tiền tệ' }}</x-head-title>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ 'Tiền mặt' }}
        </h2>
        <p>{{ 'Phiếu thu' }}</p>
    </x-slot>

    <div class="grid grid-cols-3 gap-4 items-center pt-1">
        <label class="text-right">Kỳ báo cáo</label>
        <div class="col-span-2">
            @livewire('catalog::component.timer')
        </div>
    </div>

    <div class="grid grid-cols-3 gap-4 items-center pt-1">
        <label class="text-right">Danh sách tài khoản nợ</label>
        <input class="col-span-2 border-gray-300 rounded-md shadow-sm py-1" wire:model="pTk_List" />
    </div>

    <div class="grid grid-cols-3 gap-4 items-center pt-1">
        <label class="text-right">Danh sách tài khoản có</label>
        <input class="col-span-2 border-gray-300 rounded-md shadow-sm py-1" wire:model="pTkdu_List" />
    </div>

    <div class="grid grid-cols-3 gap-4 items-center pt-1">
        <label class="text-right">Mã bộ phận</label>
        <input class="col-span-2 border-gray-300 rounded-md shadow-sm py-1" wire:model="pMa_Bp" />
    </div>

    <div class="grid grid-cols-3 gap-4 items-center pt-1">
        <label class="text-right">Mã ngoại tệ</label>
        <input class="col-span-2 border-gray-300 rounded-md shadow-sm py-1" wire:model="pMa_Nt" />
    </div>

    <div class="flex justify-end pt-1">
        <button class="px-2 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700" wire:click="submit">
            Thực hiện
        </button>
    </div>

    @isset($glCts)
    <table class="w-full text-xs border border-gray-300" wire:model.defer="glCts">
        <thead>
            <tr>
                <th class="border border-gray-300 text-left px-1">Ngày chứng từ</th>
                <th class="border border-gray-300 text-left px-1">Số chứng từ</th>
                <th class="border border-gray-300 text-left px-1">Ngày lập</th>
                <th class="border border-gray-300 text-left px-1">Diễn giải</th>
                <th class="border border-gray-300 text-left px-1">TK ghi nợ</th>
                <th class="border border-gray-300 text-left px-1">TK ghi có</th>
                <th class="border border-gray-300 text-left px-1">Số phát sinh</th>
                <th class="border border-gray-300 text-left px-1">Mã CT</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($glCts??[] as $glCt)
            <tr>
                <td class="border border-gray-300 px-1">{{ $glCt->ngay_ct }}</td>
                <td class="border border-gray-300 px-1">{{ $glCt->so_ct }}</td>
                <td class="border border-gray-300 px-1">{{ $glCt->ngay_lct }}</td>
                <td class="border border-gray-300 px-1"><pre>{{ $glCt->dien_giai }}</pre></td>
                <td class="border border-gray-300 px-1">{{ $glCt->tk }}</td>
                <td class="border border-gray-300 px-1">{{ $glCt->tk_du }}</td>
                <td class="border border-gray-300 px-1 text-right">@currency($glCt->ps_no)</td>
                <td class="border border-gray-300 px-1">{{ $glCt->ma_ct }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endisset

</div>
