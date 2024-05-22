@extends('catalog::layouts.master')
@section('title', 'Hàng hoá vật tư')

@section('content')
    <style type="text/css">
        body {
            font-size: 0.75rem
        }

        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        tr.disable {
            color: rgb(57, 44, 44);
            background-color: rgb(246, 80, 80);
        }

        td,
        th {
            border: 0px solid #dddddd;
            text-align: left;
            line-height: 0.75rem;
            padding: 4px;
        }

        button.sync {
            border: solid 1px rgb(184, 63, 63);
            border-radius: 3px;
            color: rgb(184, 63, 63);
        }

        button.sync:hover {
            cursor: pointer;
            background: rgb(184, 63, 63);
            color: rgb(241, 225, 216)
        }

        td a {
            text-decoration-line: none;
            color: rgb(10, 149, 22);
        }
    </style>
    <table class="table table-hover table-condensed table-sm text-monospace small">
        <tbody>
            @foreach ($products as $product)
                <tr id="{{ "$product->simbaId" }}" @class(['prod', 'disable' => $product->status])>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->status }}</td>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category ?: 'missing' }}</td>
                    <td>{{ $product->cat ? $product->cat->magento_id : 'NaN' }}</td>
                    <td>
                        @isset($product->cat)
                            <a href="https://www.diepxuan.com/{{ $product->cat->urlKey }}/{{ $product->urlKey }}.html"
                                target="_blank">
                                {{ $product->sku }}
                            </a>
                        @else
                            <a href="https://www.diepxuan.com/{{ $product->urlKey }}.html" target="_blank">
                                {{ $product->sku }}
                            </a>
                        @endisset
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
