<div>
    <x-head-title>{{ 'Nhóm Hàng Hoá Vật Tư' }}</x-head-title>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ 'Nhóm Hàng Hoá Vật Tư' }}
        </h2>
        <p>{{ 'Danh sách' }}</p>
    </x-slot>

    <div class="w-full overflow-x-auto">
        {{-- <div class="whitespace-pre font-mono text-xs leading-tight">
            {!! $inDmNhvts->toMarkdownTable(
                [
                    'ma_nhvt' => 'Mã nhóm vật tư',
                    'ten_nhvt_view' => 'Tên nhóm vật tư',
                    'nhom_me' => 'Nhóm mẹ',
                    'cong_sl' => 'Cộng số lượng',
                    'tk_vt' => 'TK vật tư',
                    'tk_dt' => 'TK doanh thu',
                    'tk_dtnb' => 'TK doanh thu nội bộ',
                    'tk_gv' => 'TK giá vốn',
                    'tk_tl' => 'TK trả lại',
                    'tk_ck' => 'TK chiết khấu',
                    'ksd' => 'KSD',
                    'cdate' => 'Ngày tạo',
                    'cuser' => 'Người tạo',
                    'ldate' => 'Ngày sửa',
                    'luser' => 'Người sửa',
                ],
                true,
            ) !!}
        </div> --}}

        <table class="border border-gray-200 font-mono text-xs">
            <thead>
                <tr>
                    <th></th>
                    <th class="border border-gray-200 px-1 text-left">Mã nhóm vật tư</th>
                    <th class="border border-gray-200 px-1 text-left">Tên nhóm vật tư</th>
                    <th class="border border-gray-200 px-1 text-left">Nhóm mẹ</th>
                    <th class="border border-gray-200 px-1 text-left">Cộng số lượng</th>
                    <th class="border border-gray-200 px-1 text-left">TK vật tư</th>
                    <th class="border border-gray-200 px-1 text-left">TK doanh thu</th>
                    <th class="border border-gray-200 px-1 text-left">TK doanh thu nội bộ</th>
                    <th class="border border-gray-200 px-1 text-left">TK giá vốn</th>
                    <th class="border border-gray-200 px-1 text-left">TK trả lại</th>
                    <th class="border border-gray-200 px-1 text-left">TK chiết khấu</th>
                    <th class="border border-gray-200 px-1 text-left">KSD</th>
                    <th class="border border-gray-200 px-1 text-left">Ngày tạo</th>
                    <th class="border border-gray-200 px-1 text-left">Người tạo</th>
                    <th class="border border-gray-200 px-1 text-left">Ngày sửa</th>
                    <th class="border border-gray-200 px-1 text-left">Người sửa</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inDmNhvts ?? [] as $inDmNhvt)
                    <tr class="hover:bg-sky-100">
                        <td class="border border-gray-200 px-1 text-right">{{ $loop->iteration }}</td>
                        <td class="border border-gray-200 px-1">{{ $inDmNhvt->ma_nhvt }}</td>
                        <td class="whitespace-pre border border-gray-200 px-1">{{ $inDmNhvt->ten_nhvt_view }}</td>
                        <td class="border border-gray-200 px-1">{{ $inDmNhvt->nhom_me }}</td>
                        <td class="border border-gray-200 px-1 text-center">{{ $inDmNhvt->cong_sl }}</td>
                        <td class="border border-gray-200 px-1">{{ $inDmNhvt->tk_vt }}</td>
                        <td class="border border-gray-200 px-1">{{ $inDmNhvt->tk_dt }}</td>
                        <td class="border border-gray-200 px-1">{{ $inDmNhvt->tk_dtnb }}</td>
                        <td class="border border-gray-200 px-1">{{ $inDmNhvt->tk_gv }}</td>
                        <td class="border border-gray-200 px-1">{{ $inDmNhvt->tk_tl }}</td>
                        <td class="border border-gray-200 px-1">{{ $inDmNhvt->tk_ck }}</td>
                        <td class="border border-gray-200 px-1">{{ $inDmNhvt->ksd }}</td>
                        <td class="border border-gray-200 px-1">{{ $inDmNhvt->cdate }}</td>
                        <td class="border border-gray-200 px-1">{{ $inDmNhvt->cuser }}</td>
                        <td class="border border-gray-200 px-1">{{ $inDmNhvt->ldate }}</td>
                        <td class="border border-gray-200 px-1">{{ $inDmNhvt->luser }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
