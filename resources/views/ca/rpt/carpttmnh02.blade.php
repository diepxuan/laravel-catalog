<div class="phieuthu-container w-full">
    <x-head-title>{{ 'Báo cáo Ngân hàng - Tiền tệ' }}</x-head-title>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ 'Sổ quỹ chuyển khoản ngân hàng' }}
        </h2>
    </x-slot>

    <div class="grid grid-cols-3 gap-4 items-center pt-1">
        <label class="text-right">Kỳ báo cáo</label>
        <div class="col-span-2">
            @livewire('catalog::component.timer')
        </div>
    </div>

    <div class="grid grid-cols-3 gap-4 items-center pt-1">
        <label class="text-right">Danh sách tài khoản</label>
        <input class="col-span-2 border-gray-300 rounded-md shadow-sm py-1" wire:model="pTk" />
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
    <table class="w-full mt-3 text-xs border border-gray-200">
        <thead>
            <tr>
                <th></th>
                <th class="border border-gray-200 text-left px-1">Ngày chứng từ</th>
                <th class="border border-gray-200 text-left px-1">Số chứng từ</th>
                <th class="border border-gray-200 text-left px-1">Diễn giải</th>
                <th class="border border-gray-200 text-left px-1">Khách hàng</th>
                <th class="border border-gray-200 text-left px-1">Thu</th>
                <th class="border border-gray-200 text-left px-1">Chi</th>
                <th class="border border-gray-200 text-left px-1">Tồn</th>
                <th class="border border-gray-200 text-left px-1">Mã CT</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($glCts??[] as $glCt)
            {{-- @dd($glCt->arDmKh) --}}
            <tr @class([
                'hover:bg-sky-100',
                'text-blue-700 hover:bg-sky-100 border border-gray-200' => $glCt->bold,
            ])>
                <td class="border border-gray-300 px-1">{{ $loop->iteration }}</td>
                <td class="border border-gray-200 px-1">{{ $glCt->ngay_ct }}</td>
                <td class="border border-gray-200 px-1">{{ $glCt->so_ct }}</td>
                <td class="border border-gray-200 px-1"><pre>{{ $glCt->dien_giai }}</pre></td>
                <td class="border border-gray-200 px-1">{{ $glCt->arDmKh?->ten_kh }}</td>
                <td class="border border-gray-200 px-1 text-right">@currency($glCt->ps_no)</td>
                <td class="border border-gray-200 px-1 text-right">@currency($glCt->ps_co)</td>
                <td class="border border-gray-200 px-1 text-right">@currency($glCt->so_du)</td>
                <td class="border border-gray-200 px-1">{{ $glCt->ma_ct }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endisset

</div>
