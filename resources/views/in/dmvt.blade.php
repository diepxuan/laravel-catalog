<div>
    <x-head-title>{{ 'Hàng Hoá Vật Tư' }}</x-head-title>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ 'Hàng Hoá Vật Tư' }}
        </h2>
        <p>{{ 'Danh sách' }}</p>
    </x-slot>
    <div class="w-full overflow-x-auto">
        {{-- <pre class="prose prose-sm w-full">
            | STT | Mã vật tư | Tên vật tư | ĐVT | Mã Nhóm | Mã Kho | Mã vị trí | TK VT | TK DT | TK DTNB | TK GV | TK TL | TK CK | Tính tồn kho | Loại | Tên loại | Giá tồn | Tên loại giá | Tính giá thành | Mã nhóm VAT | Nước sản xuất | Mã PL VT 1 | Mã PL VT 2 | Mã PL VT 3 | ĐVT bán | HS ĐVT bán | ĐVT mua | HS ĐVT mua | Mã thuế | SL min | SL max | Nhà SX | Nhà PP | KSD | Ngày tạo | Người tạo | Ngày sửa | Người sửa |
            | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
            | 1 | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | | |
        </pre> --}}
        <pre class="text-xs">{!! $inDmVts !!}</pre>
        {{-- <article class="prose prose-sm w-full">
            {!! Str::of($inDmVts)->markdown() !!}
        </article> --}}
        {{-- <table class="border border-gray-300 text-xs">
            <thead>
                <tr>
                    <th class="sticky left-0"></th>
                    <th class="border border-gray-300 text-left">Mã vật tư</th>
                    <th class="border border-gray-300 text-left">Tên vật tư</th>
                    <th class="border border-gray-300 text-left">ĐVT</th>
                    <th class="border border-gray-300 text-left">Mã Nhóm</th>
                    <th class="border border-gray-300 text-left">Mã Kho</th>
                    <th class="border border-gray-300 text-left">Mã vị trí</th>
                    <th class="border border-gray-300 text-left">TK VT</th>
                    <th class="border border-gray-300 text-left">TK DT</th>
                    <th class="border border-gray-300 text-left">TK DTNB</th>
                    <th class="border border-gray-300 text-left">TK GV</th>
                    <th class="border border-gray-300 text-left">TK TL</th>
                    <th class="border border-gray-300 text-left">TK CK</th>
                    <th class="border border-gray-300 text-left">Tính tồn kho</th>
                    <th class="border border-gray-300 text-left">Loại</th>
                    <th class="border border-gray-300 text-left">Tên loại</th>
                    <th class="border border-gray-300 text-left">Giá tồn</th>
                    <th class="border border-gray-300 text-left">Tên loại giá</th>
                    <th class="border border-gray-300 text-left">Tính giá thành</th>
                    <th class="border border-gray-300 text-left">Mã nhóm VAT</th>
                    <th class="border border-gray-300 text-left">Nước sản xuất</th>
                    <th class="border border-gray-300 text-left">Mã PL VT 1</th>
                    <th class="border border-gray-300 text-left">Mã PL VT 2</th>
                    <th class="border border-gray-300 text-left">Mã PL VT 3</th>
                    <th class="border border-gray-300 text-left">ĐVT bán</th>
                    <th class="border border-gray-300 text-left">HS ĐVT bán</th>
                    <th class="border border-gray-300 text-left">ĐVT mua</th>
                    <th class="border border-gray-300 text-left">HS ĐVT mua</th>
                    <th class="border border-gray-300 text-left">Mã thuế</th>
                    <th class="border border-gray-300 text-left">SL min</th>
                    <th class="border border-gray-300 text-left">SL max</th>
                    <th class="border border-gray-300 text-left">Nhà SX</th>
                    <th class="border border-gray-300 text-left">Nhà PP</th>
                    <th class="border border-gray-300 text-left">KSD</th>
                    <th class="border border-gray-300 text-left">Ngày tạo</th>
                    <th class="border border-gray-300 text-left">Người tạo</th>
                    <th class="border border-gray-300 text-left">Ngày sửa</th>
                    <th class="border border-gray-300 text-left">Người sửa</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inDmVts as $inDmVt)
                    <tr>
                        <td class="border border-gray-300 px-1">{{ $loop->iteration }}</td>
                        <td class="border border-gray-300">{{ $inDmVt->ma_vt }}</td>
                        <td class="border border-gray-300 pl-1">
                            <pre>{{ $inDmVt->ten_vt }}</pre>
                        </td>
                        <td class="border border-gray-300">{{ $inDmVt->dvt }}</td>
                        <td class="border border-gray-300">{{ $inDmVt->ma_nhvt }}</td>
                        <td class="border border-gray-300">{{ $inDmVt->ma_kho }}</td>
                        <td class="border border-gray-300">{{ $inDmVt->ma_vitri }}</td>
                        <td class="border border-gray-300">{{ $inDmVt->tk_vt }}</td>
                        <td class="border border-gray-300">{{ $inDmVt->tk_dt }}</td>
                        <td class="border border-gray-300">{{ $inDmVt->tk_dtnb }}</td>
                        <td class="border border-gray-300">{{ $inDmVt->tk_gv }}</td>
                        <td class="border border-gray-300">{{ $inDmVt->tk_tl }}</td>
                        <td class="border border-gray-300">{{ $inDmVt->tk_ck }}</td>
                        <td class="border border-gray-300 text-center text-blue-700">
                            {{ $inDmVt->ksd ? '✔' : '' }}
                        </td>
                        <td class="border border-gray-300">{{ $inDmVt->loai }}</td>
                        <td class="border border-gray-300">{{ $inDmVt->ten_loai }}</td>
                        <td class="border border-gray-300">{{ $inDmVt->gia_ton }}</td>
                        <td class="border border-gray-300">{{ $inDmVt->ten_loai_gia }}</td>
                        <td class="border border-gray-300">{{ $inDmVt->tinh_gt }}</td>
                        <td class="border border-gray-300">{{ $inDmVt->ma_nhvat }}</td>
                        <td class="border border-gray-300">{{ $inDmVt->nuoc_sx }}</td>
                        <td class="border border-gray-300">{{ $inDmVt->ma_plvt1 }}</td>
                        <td class="border border-gray-300">{{ $inDmVt->ma_plvt2 }}</td>
                        <td class="border border-gray-300">{{ $inDmVt->ma_plvt3 }}</td>
                        <td class="border border-gray-300">{{ $inDmVt->dvt_ban }}</td>
                        <td class="border border-gray-300">{{ $inDmVt->hs_dvtban }}</td>
                        <td class="border border-gray-300">{{ $inDmVt->dvt_mua }}</td>
                        <td class="border border-gray-300">{{ $inDmVt->hs_dvtmua }}</td>
                        <td class="border border-gray-300">{{ $inDmVt->ma_thue }}</td>
                        <td class="border border-gray-300">{{ $inDmVt->sl_min }}</td>
                        <td class="border border-gray-300">{{ $inDmVt->sl_max }}</td>
                        <td class="border border-gray-300">{{ $inDmVt->nha_sx }}</td>
                        <td class="border border-gray-300">{{ $inDmVt->nha_pp }}</td>
                        <td class="border border-gray-300 text-center text-blue-700">
                            {{ $inDmVt->ksd ? '✔' : '' }}
                        </td>
                        <td class="border border-gray-300">{{ $inDmVt->cdate }}</td>
                        <td class="border border-gray-300">{{ $inDmVt->cuser }}</td>
                        <td class="border border-gray-300">{{ $inDmVt->ldate }}</td>
                        <td class="border border-gray-300">{{ $inDmVt->luser }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table> --}}
    </div>

</div>
