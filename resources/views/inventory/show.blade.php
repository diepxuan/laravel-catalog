@php
    if (isset($prevPhdck)) {
        $prevLink = route('inventory.show', array_merge(['tonkho' => $prevPhdck->getKey()], request()->query()));
    }
    if (isset($nextPhdck)) {
        $nextLink = route('inventory.show', array_merge(['tonkho' => $nextPhdck->getKey()], request()->query()));
    }
@endphp
@extends('catalog::layouts.master')
@section('title', "{$phdck->ngayCt->format('d/m/Y')} {$phdck->dien_giai}")

@section('content')
    <style type="text/css">
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
    <table class="lstChungTu" id="lstChungTu">
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
            <tr tabindex="0">
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
            var lstCt = document.querySelectorAll("[id=lstChungTu] tr");
            var curCt = document.activeElement;
            if (Array.prototype.indexOf.call(lstCt, curCt) <= 0) {
                curCt = lstCt.item(1);
            } else {
                if (key === 38) { // len
                    event.preventDefault();
                    curCt = lstCt.item(Array.prototype.indexOf.call(lstCt, curCt) - 1)
                } else if (key === 40) { // xuong
                    event.preventDefault();
                    curCt = lstCt.item(Array.prototype.indexOf.call(lstCt, curCt) + 1)
                }
            }
            curCt.focus();
        }
    </script>
@endsection
