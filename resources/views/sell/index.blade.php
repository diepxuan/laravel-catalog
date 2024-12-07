@extends('catalog::layouts.master')
@section('title', 'Phiếu xuất điều chuyển kho')

@section('content')
    <style type="text/css">
        ul.autocomplete {
            background: #ffffff;
            border: solid 1px #c3c3c3;
            border-radius: 3px;
            box-shadow: 0px 5px 5px #29292961;
            display: block;
            position: absolute;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        ul.autocomplete li {
            cursor: pointer;
            padding: 0 4px;
        }

        ul.autocomplete li i {
            font-size: 0.75rem;
        }

        ul.autocomplete li+li {
            border-top: solid 1px #c3c3c3;
        }

        ul.autocomplete,
        ul.autocomplete li {
            display: none;
        }
    </style>

    <form action="{{ route('inventory.index') }}" method="GET">
        @method('GET') @csrf
        <table>
            <tbody>
                <tr>
                    <td>{{ 'Thời gian' }}</td>
                    <td>
                        <label>
                            <span>Từ</span>
                            <input type="date" value="{{ $from->format('Y-m-d') }}" placeholder="dd/mm/yyyy"
                                name="from" />
                        </label>
                    </td>
                    <td>
                        <label>
                            <span>Đến</span>
                            <input type="date" value="{{ $to->format('Y-m-d') }}" placeholder="dd/mm/yyyy" name="to" />
                        </label>
                    </td>
                </tr>
                <tr>
                    <td>{{ 'Kho Xuất' }}</td>
                    <td>
                        <input type="text" value="{{ strtoupper($khoxuat ?: '') }}" oninput="autocompleteShow(this)"
                            onblur="closeAllLists()" name="khoxuat" />
                        <ul class="autocomplete">
                            @foreach ($lstKho as $kho)
                                <li data-khoid="{{ $kho->sku }}" onclick="autocompleteSelect(this)">
                                    <span>{{ $kho->name }}</span>
                                    <i>{{ $kho->sku }}</i>
                                </li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" value="Nhận" /></td>
                </tr>
            </tbody>
        </table>
    </form>
    <table class="table table-sm align-middle table-hover">
        @foreach ($lstPhdck as $index => $phdck)
            <tr>
                {{-- <td>{{ $index }}</td> --}}
                @php
                    $link = route('inventory.show', array_merge(['tonkho' => $phdck->getKey()], request()->query()));
                @endphp
                <td>
                    <a href="{{ $link }}" class="text-decoration-none" target="_blank">
                        {{ $phdck->so_ct }}
                    </a>
                </td>
                <td>{{ $phdck->ngayCt->format('d/m/Y') }}</td>
                <td>
                    {{ $phdck->dien_giai }}
                    <p class="mb-0"><small>{{ $phdck->getKey() }}</small></p>
                </td>
                {{-- <td class="right">{{ $phdck->soLuong }}</td> --}}
                {{-- <td class="right">{{ $phdck->soTien }}</td> --}}
            </tr>
        @endforeach
    </table>
    <script type="text/javascript">
        function autocompleteShow(inp) {
            autocomplete = getNextElementByClass(inp, 'autocomplete');
            autocomplete.style.display = "none";
            for (const element of autocomplete.children) {
                element.style.display = "none";
                if (element.dataset.khoid.toLowerCase().includes(inp.value.toLowerCase())) {
                    element.style.display = "block";
                    autocomplete.style.display = "block";
                }

            }

            function getNextElementByClass(element, className) {
                if (element.nextElementSibling)
                    if (element.nextElementSibling.classList.contains(className))
                        return element.nextElementSibling;
                    else
                        return getNextElementByClass((element.nextElementSibling, className))
                else return null
            }
        }

        function autocompleteSelect(sel) {
            console.log(sel, sel.parentElement);
        }

        function closeAllLists() {
            for (const element of document.getElementsByClassName("autocomplete")) {
                element.style.display = "none"
            }
        }
    </script>
@endsection
