@php
    if (isset($prevPhdck)) {
        $prevLink = route('inventory.show', array_merge(['tonkho' => $prevPhdck->getKey()], request()->query()));
    }
    if (isset($nextPhdck)) {
        $nextLink = route('inventory.show', array_merge(['tonkho' => $nextPhdck->getKey()], request()->query()));
    }
@endphp
@extends('catalog::layouts.master')
@section('title', "{$phdck->ngayCt->format('d/m/y')} {$phdck->dien_giai}")

@section('content')
    <style type="text/css">
        [id=lstChungTu] tr:focus,
        [id=lstChungTu] tr:focus-visible {
            outline: none;
            background-color: rgba(0, 0, 0, 0.03);
        }

        [id=lstChungTu] tr:focus td,
        [id=lstChungTu] tr:focus-visible td {
            outline: none;
            background-color: rgba(0, 0, 0, 0.03);
        }
    </style>
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
            <td><b>{{ $phdck->ngayCt->format('d/m/y') }}</b></td>
        </tr>
        <tr>
            <td>Người giao dịch</td>
            <td>{{ $phdck->nguoi_gd }}</td>
            <td>Ngày lập</td>
            <td>{{ $phdck->ngayLct->format('d/m/y') }}</td>
        </tr>
        <tr>
            <td>Diễn giải</td>
            <td>{{ $phdck->dien_giai }}</td>
        </tr>
    </table>
    <table class="table table-sm align-middle table-hover align-middle" id="lstChungTu">
        @foreach ($phdck->items as $index => $chungtu)
            <tr tabindex="0">
                <td>
                    <p class="mb-0 fw-semibold">{{ $chungtu->product->name }}
                        <a href="{{ route('catalog.show', $chungtu->product->id) }}"
                            class="text-decoration-none small text-secondary-emphasis" target="_blank">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                    </p>
                    <p class="mb-0 fw-lighter"><small>{{ $chungtu->ma_vt }}</small></p>
                </td>
                <td class="text-nowrap fw-semibold">
                    {{ (float) number_format((float) $chungtu->so_luong, 1) }}
                    <span class="fw-light text-lowercase"><small>{{ $chungtu->dvt }}</small></span>
                </td>
                <td class="text-nowrap fw-light">
                    <small>{{ $chungtu->ma_khox }} &#8594; {{ $chungtu->ma_khon }}</small>
                </td>
                {{-- <td class="right">{{ number_format((float) $chungtu->gia, 0) }}</td> --}}
                {{-- <td class="right">{{ number_format((float) $chungtu->tien, 0) }}</td> --}}
                {{-- <td>{{ $chungtu->tk_vt }}</td> --}}
                {{-- <td>{{ $chungtu->tk_no }}</td> --}}
            </tr>
        @endforeach
    </table>
    <nav>
        <ul class="pagination">
            @isset($prevLink)
                <li class="page-item">
                    <a id="phNext" class="page-link" href="{{ $prevLink }}">
                        <span aria-hidden="true">&laquo;</span>
                        {{ $prevPhdck->dien_giai }}
                    </a>
                </li>
            @endisset
            <li class="page-item disabled">
                <a class="page-link" href="#">{{ __('phiếu trước hoặc phiếu tiếp theo') }}</a>
            </li>
            @isset($nextLink)
                <li class="page-item">
                    <a id="phPrev" class="page-link" href="{{ $nextLink }}">
                        {{ $nextPhdck->dien_giai }}
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            @endisset
        </ul>
    </nav>
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
            if (key === 38 || key === 40) {
                event.preventDefault();
            }
            var lstCt = document.querySelectorAll("[id=lstChungTu] tr");
            var curCt = document.activeElement;
            if (Array.prototype.indexOf.call(lstCt, curCt) < 0) {
                curCt = lstCt.item(0);
            } else {
                if (key === 38) { // len
                    curCt = lstCt.item(Array.prototype.indexOf.call(lstCt, curCt) - 1)
                } else if (key === 40) { // xuong
                    curCt = lstCt.item(Array.prototype.indexOf.call(lstCt, curCt) + 1)
                }
            }
            curCt.focus();
        }
    </script>
@endsection
