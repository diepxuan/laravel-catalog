<x-catalog::app-layout>
    <x-catalog::head-title>{{ __('Bán Hàng') }}</x-catalog::head-title>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bán Hàng') }}

        </h2>
        <p>{{ __('Hoá đơn bán hàng') }}</p>
    </x-slot>

    <form method="GET">
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
                            <input type="date" value="{{ $to->format('Y-m-d') }}" placeholder="dd/mm/yyyy"
                                name="to" />
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
                    // $link = route('inventory.show', array_merge(['tonkho' => $phdck->getKey()], request()->query()));
                    $link = '';
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

</x-catalog::app-layout>
