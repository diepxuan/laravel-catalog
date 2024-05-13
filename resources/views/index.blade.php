@extends('catalog::layouts.master')

@section('content')
    <style>
        body {
            font-size: 0.75rem
        }

        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        tr {
            font-size: 0.5rem;
        }

        tr.prod {
            color: rgb(166, 59, 40);
            font-size: 0.75rem;
            font-weight: 700;
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
            border-radius: 10%;
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
                <tr id="{{ "$product->simbaId" }}" @class(['prod', 'font-bold' => true])>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category ?: 'ungroup' }}</td>
                </tr>
                @isset($product->simba)
                    <tr>
                        <td></td>
                        <td>{{ $product->simbaId }}</td>
                        <td>{{ $product->simba->ten_vt }}</td>
                        <td>{{ $product->simba->ma_nhvt ?: 'ungroup' }}</td>
                    </tr>
                @else
                    <tr>
                        <td></td>
                        <td>Simba empty</td>
                    </tr>
                @endisset
                @isset($product->magento)
                    <tr>
                        <td></td>
                        <td>
                            <a href="https://www.diepxuan.com/catalog/product/view/id/{{ $product->magentoId }}" target="_blank">
                                {{ $product->magento->sku }}
                            </a>
                        </td>
                        <td>{{ $product->magento->name }}</td>
                        <td>
                            <a href="https://www.diepxuan.com/{{ $product->magento->custom_attributes->url_key }}.html"
                                target="_blank">
                                {{ $product->magento->custom_attributes->url_key }}
                            </a>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td></td>
                        <td>Magento empty</td>
                        <td>
                            <form method="post" action="{{ route('catalog.store') }}" target="_blank" name="magento_form">
                                @method('POST') @csrf
                                <input type="hidden" value="magento2" name="type" />
                                <input type="hidden" value="{{ $product->sku }}" name="sku" />
                                <input type="hidden" value="{{ $product->name }}" name="name" />
                                <button type="submit" class="sync">create</button>
                            </form>
                        </td>
                    </tr>
                @endisset
            @endforeach
        </tbody>
    </table>
@endsection
