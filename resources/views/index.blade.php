@extends('catalog::layouts.master')

@section('content')
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }

        body {
            counter-reset: Serial;
            /* Set the Serial counter to 0 */
        }

        table {
            border-collapse: separate;
        }

        tr td:first-child:before {
            counter-increment: Serial;
            /* Increment the Serial counter */
            content: counter(Serial);
            /* Display the counter */
        }

        button.sync {
            border: solid 1px rgb(184, 63, 63);
            border-radius: 10%;
            color: rgb(184, 63, 63);
        }

        td a {
            text-decoration-line: none;
            color: rgb(10, 149, 22);
        }
    </style>
    <table class="table table-hover table-condensed table-sm text-monospace small">
        <tbody>
            @foreach ($products as $product)
                @php
                    $mProduct = null;
                    $mProduct = $mProducts->first(function ($item) use ($product) {
                        return $item->sku == $product->ma_vt;
                    });
                @endphp
                <tr id="{{ "$product->ma_cty|$product->ma_vt" }}">
                    <td></td>
                    <td>{{ $product->ma_vt }}</td>
                    <td>{{ $product->ten_vt }}</td>
                    <td>{{ $product->ma_nhvt }}</td>
                    <td>
                        @if (isset($mProduct))
                            {{-- @dd($mProduct) --}}
                            <a href="{{ "https://www.diepxuan.com/catalog/product/view/id/$mProduct->id" }}">magento</a>
                        @else
                            <form method="post" action="{{ route('catalog.store') }}">
                                @method('POST') @csrf
                                <input type="hidden" value="magento2" name="type" />
                                <input type="hidden" value="{{ $product->ma_vt }}" name="sku" />
                                <input type="hidden" value="{{ $product->ten_vt }}" name="name" />
                                <button type="submit" class="sync">sync</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
