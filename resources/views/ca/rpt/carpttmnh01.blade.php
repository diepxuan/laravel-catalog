<div class="phieuthu-container w-full">
    <x-head-title>{{ 'Báo cáo tiền mặt - Tiền tệ' }}</x-head-title>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ 'Sổ quỹ tiền mặt' }}
        </h2>
    </x-slot>

    <div class="grid grid-cols-3 items-center gap-4 pt-1">
        <label class="text-right">Kỳ báo cáo</label>
        <div class="col-span-2">
            @livewire('catalog::component.timer')
        </div>
    </div>

    <div class="grid grid-cols-3 items-center gap-4 pt-1">
        <label class="text-right">Danh sách tài khoản</label>
        <input class="col-span-2 rounded-md border-gray-300 py-1 shadow-sm" wire:model="pTk" />
    </div>

    <div class="grid grid-cols-3 items-center gap-4 pt-1">
        <label class="text-right">Mã ngoại tệ</label>
        <input class="col-span-2 rounded-md border-gray-300 py-1 shadow-sm" wire:model="pMa_Nt" />
    </div>

    <div class="flex justify-end pt-1">
        <button class="rounded-md bg-blue-600 px-2 py-1 text-white hover:bg-blue-700" wire:click="submit">
            Thực hiện
        </button>
    </div>

    @isset($glCts)
        <table class="mt-3 w-full border border-gray-200 text-xs">
            <thead>
                <tr>
                    <th></th>
                    <th class="border border-gray-200 px-1 text-left">Ngày chứng từ</th>
                    <th class="border border-gray-200 px-1 text-left">Số chứng từ</th>
                    <th class="border border-gray-200 px-1 text-left">Diễn giải</th>
                    <th class="border border-gray-200 px-1 text-left">Khách hàng</th>
                    <th class="border border-gray-200 px-1 text-left">Thu</th>
                    <th class="border border-gray-200 px-1 text-left">Chi</th>
                    <th class="border border-gray-200 px-1 text-left">Tồn</th>
                    <th class="border border-gray-200 px-1 text-left">Mã CT</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($glCts ?? [] as $glCt)
                    {{-- @dd($glCt->arDmKh) --}}
                    <tr @class([
                        'hover:bg-sky-100',
                        'text-blue-700 hover:bg-sky-100 border border-gray-200' => $glCt->bold,
                    ])>
                        <td class="border border-gray-300 px-1">{{ $loop->iteration }}</td>
                        <td class="border border-gray-200 px-1">{{ $glCt->ngay_ct }}</td>
                        <td class="border border-gray-200 px-1">{{ $glCt->so_ct }}</td>
                        <td class="border border-gray-200 px-1">
                            <pre>{{ $glCt->dien_giai }}</pre>
                        </td>
                        <td class="border border-gray-200 px-1">{{ $glCt->ten_kh }}</td>
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
