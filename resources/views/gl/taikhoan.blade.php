<div>
    <x-head-title>{{ __('Tổng hợp') }}</x-head-title>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tổng hợp') }}
        </h2>
        <p>{{ __('Tài khoản kế toán') }}</p>
    </x-slot>

    <table class="w-full text-xs border border-gray-300">
        <thead>
            <tr>
                <th class="border border-gray-300 text-left">TK</th>
                <th class="border border-gray-300 text-left">Tên tài khoản</th>
                <th class="border border-gray-300 text-left">Mã NTệ</th>
                <th class="border border-gray-300 text-left">TK mẹ</th>
                <th class="border border-gray-300 text-left">TK Công nợ</th>
                <th class="border border-gray-300 text-left">Kiểu SD</th>
                <th class="border border-gray-300 text-left">TK chi tiết</th>
                <th class="border border-gray-300 text-left">TK sổ cái</th>
                <th class="border border-gray-300 text-left">PP tính TGGS nợ</th>
                <th class="border border-gray-300 text-left">PP tính TGGS có</th>
                <th class="border border-gray-300 text-left">KSD</th>
                <th class="border border-gray-300 text-left">Ngày tạo</th>
                <th class="border border-gray-300 text-left">Người tạo</th>
                <th class="border border-gray-300 text-left">Ngày sửa</th>
                <th class="border border-gray-300 text-left">Người sửa</th>
                <th class="border border-gray-300 text-center">Bậc TK</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($glDmTks as $glDmTk)
                {{-- @dd($glDmTk) --}}
                <tr wire:loading.class.delay="opacity-50"
                    @class([
                        $glDmTk->bold ? 'text-blue-700 dark:text-sky-400' : '',
                    ]) >
                    <td class="border border-gray-300">{{ $glDmTk->tk }}</td>
                    <td class="border border-gray-300 pl-1"><pre>{!! str_repeat(' ', max(0, ($glDmTk->bac_tk - 1) * 3)) . $glDmTk->ten_tk !!}</pre></td>
                    <td class="border border-gray-300">{{ $glDmTk->ma_nt }}</td>
                    <td class="border border-gray-300">{{ $glDmTk->tk_me }}</td>
                    <td class="border border-gray-300 text-center"><x-checkbox :checked="$glDmTk->tk_cn" /></td>
                    <td class="border border-gray-300 text-center">{{ $glDmTk->kieu_sd }}</td>
                    <td class="border border-gray-300 text-center"><x-checkbox :checked="$glDmTk->tk_chi_tiet" /></td>
                    <td class="border border-gray-300 text-center"><x-checkbox :checked="$glDmTk->tk_sc" /></td>
                    <td class="border border-gray-300 text-center">{{ $glDmTk->pp_tinh_tggs_no }}</td>
                    <td class="border border-gray-300 text-center">{{ $glDmTk->pp_tinh_tggs_co }}</td>
                    <td class="border border-gray-300 text-center"><x-checkbox :checked="$glDmTk->ksd" /></td>
                    <td class="border border-gray-300">{{ $glDmTk->cdate }}</td>
                    <td class="border border-gray-300">{{ $glDmTk->cuser }}</td>
                    <td class="border border-gray-300">{{ $glDmTk->ldate }}</td>
                    <td class="border border-gray-300">{{ $glDmTk->luser }}</td>
                    <td class="border border-gray-300 text-center">{{ $glDmTk->bac_tk }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
