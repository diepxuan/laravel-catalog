@extends('catalog::layouts.master')
@section('title', 'Phiếu xuất điều chuyển kho')

@section('content')
    <style type="text/css">
        .right {
            text-align: right;
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
            border-top: 1px solid black;
        }
    </style>
    <table>
        <tr>
            <td><small>{{ $phdck->getKey() }}</small></td>
            <td></td>
            <td>Ngày xuất</td>
            <td>{{ $phdck->ngayCt }}</td>
        </tr>
        <tr>
            <td>Khách hàng</td>
            <td>
                <b>{{ $phdck->ten_kh }}</b>
                <small>[{{ $phdck->ma_kh }}]</small>
            </td>
            <td>Ngày lập</td>
            <td>{{ $phdck->ngayLct }}</td>
        </tr>
        <tr>
            <td>Người giao dịch</td>
            <td>{{ $phdck->nguoi_gd }}</td>
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
            <th>Đvt</th>
            <th>Kho</th>
            <th>Kho nhập</th>
            <th>Số lượng</th>
            <th>Đơn giá</th>
            <th>Thành tiền</th>
            <th>TK nợ</th>
            <th>TK có</th>
        </tr>
        @foreach ($phdck->chungtu as $chungtu)
            <tr>
                <td>{{ $chungtu->ma_vt }}</td>
                <td>{{ $chungtu->ten_vt }}</td>
                <td>{{ $chungtu->dvt }}</td>
                <td>{{ $chungtu->ma_khox }}</td>
                <td>{{ $chungtu->ma_khon }}</td>
                <td class="right">{{ number_format((float) $chungtu->so_luong, 1) }}</td>
                <td class="right">{{ number_format((float) $chungtu->gia, 0) }}</td>
                <td class="right">{{ number_format((float) $chungtu->tien, 0) }}</td>
                <td>{{ $chungtu->tk_vt }}</td>
                <td>{{ $chungtu->tk_no }}</td>
            </tr>
        @endforeach
    </table>
    <script type="text/javascript"></script>
@endsection
