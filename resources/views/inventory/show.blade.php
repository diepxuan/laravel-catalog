@php
    if (isset($prevPhdck)) {
        $prevLink = route('inventory.show', array_merge(['tonkho' => $prevPhdck->getKey()], request()->query()));
    }
    if (isset($nextPhdck)) {
        $nextLink = route('inventory.show', array_merge(['tonkho' => $nextPhdck->getKey()], request()->query()));
    }
@endphp
@extends('catalog::layouts.master')
@section('title', 'Phiếu xuất điều chuyển kho')

@section('content')
    <style type="text/css">
        .right {
            text-align: right;
        }

        ul.pagination {
            display: block;
            float: left;
            list-style: none;
            padding: 0;
            margin: 0;
            width: 100%;
        }

        ul.pagination li {
            display: block;
            float: left;
        }

        ul.pagination li+li {
            float: right;
        }

        ul.pagination li a {
            display: block;
            padding: 1px 6px;
            border: 1px outset buttonborder;
            border-radius: 3px;
            color: buttontext;
            background-color: buttonface;
            text-decoration: none;
        }

        table th,
        td {
            padding: 0 0.5rem;
        }

        table+table {
            margin-top: 2rem;
        }

        table.lstChungTu {
            border-collapse: collapse;
        }

        table.lstChungTu tr td {
            border-top: 1px solid rgb(224, 224, 224);
        }
    </style>
    <ul class="pagination">
        <li>
            @isset($prevLink)
                <a id="phNext" href="{{ $prevLink }}">phiếu trước: {{ $prevPhdck->dien_giai }}</a>
            @endisset
        </li>
        <li>
            @isset($nextLink)
                <a id="phPrev" href="{{ $nextLink }}">phiếu tiếp theo: {{ $nextPhdck->dien_giai }}</a>
            @endisset
        </li>
    </ul>
    <table>
        <tr>
            <td><small>{{ $phdck->getKey() }}</small></td>
            <td></td>
        </tr>
        <tr>
            <td>Khách hàng</td>
            <td>
                <b>{{ $phdck->ten_kh }}</b>
                <small>[{{ $phdck->ma_kh }}]</small>
            </td>
            <td>Ngày xuất</td>
            <td><b>{{ $phdck->ngayCt->format('d/m/Y') }}</b></td>
        </tr>
        <tr>
            <td>Người giao dịch</td>
            <td>{{ $phdck->nguoi_gd }}</td>
            <td>Ngày lập</td>
            <td>{{ $phdck->ngayLct->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td>Diễn giải</td>
            <td>{{ $phdck->dien_giai }}</td>
        </tr>
    </table>
    <table class="lstChungTu">
        <tr>
            <th>Mã hàng</th>
            <th>Tên hàng</th>
            <th>Số lượng</th>
            <th>Đvt</th>
            <th>Kho</th>
            <th>Kho nhập</th>
            {{-- <th>Đơn giá</th> --}}
            {{-- <th>Thành tiền</th> --}}
            {{-- <th>TK nợ</th> --}}
            {{-- <th>TK có</th> --}}
        </tr>
        @foreach ($phdck->chungtu as $index => $chungtu)
            <tr>
                <td>{{ $chungtu->ma_vt }}</td>
                <td>{{ $chungtu->ten_vt }}</td>
                <td class="right">{{ number_format((float) $chungtu->so_luong, 1) }}</td>
                <td>{{ $chungtu->dvt }}</td>
                <td>{{ $chungtu->ma_khox }}</td>
                <td>{{ $chungtu->ma_khon }}</td>
                {{-- <td class="right">{{ number_format((float) $chungtu->gia, 0) }}</td> --}}
                {{-- <td class="right">{{ number_format((float) $chungtu->tien, 0) }}</td> --}}
                {{-- <td>{{ $chungtu->tk_vt }}</td> --}}
                {{-- <td>{{ $chungtu->tk_no }}</td> --}}
            </tr>
        @endforeach
    </table>
    <script type="text/javascript">
        window.addEventListener("keydown", changePage);

        function changePage(event) {
            var key = event.keyCode;
            if (key === 37) {
                window.location.href = document.getElementById("phNext").href
            } else if (key === 39) {
                window.location.href = document.getElementById("phPrev").href
            }
        }

        window.addEventListener("keydown", changeSelect);

        function changeSelect(event) {
            var key = event.keyCode;
            if (key === 38) { // len
                console.log(key);
            } else if (key === 40) { // xuong
                console.log(key);
            }
        }
    </script>
@endsection
