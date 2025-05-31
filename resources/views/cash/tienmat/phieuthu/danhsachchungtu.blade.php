
    @isset($glCts)
    <table class="w-full text-xs border border-gray-300" wire:model="glCts">
        <thead>
            <tr>
                <th class="border border-gray-300 text-left">Ngày chứng từ</th>
                <th class="border border-gray-300 text-left">Số chứng từ</th>
                <th class="border border-gray-300 text-left">Ngày lập</th>
                <th class="border border-gray-300 text-left">Diễn giải</th>
                <th class="border border-gray-300 text-left">TK ghi nợ</th>
                <th class="border border-gray-300 text-left">TK ghi có</th>
                <th class="border border-gray-300 text-left">Số phát sinh</th>
                <th class="border border-gray-300 text-left">Mã CT</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($glCts??[] as $glCt)
            @dd($glCt)
            <tr>
                <td class="border border-gray-300">{{ $glCt->ngay_ct }}</td>
                <td class="border border-gray-300">{{ $glCt->so_ct }}</td>
                <td class="border border-gray-300">{{ $glCt->ngay_lct }}</td>
                <td class="border border-gray-300">{{ $glCt->dien_giai }}</td>
                <td class="border border-gray-300">{{ $glCt->tk }}</td>
                <td class="border border-gray-300">{{ $glCt->tk_du }}</td>
                <td class="border border-gray-300 text-right">{{ $glCt->ps_no }}</td>
                <td class="border border-gray-300">{{ $glCt->ma_ct }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endisset
