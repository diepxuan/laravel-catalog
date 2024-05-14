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
            @foreach ($categories as $category)
                <tr>
                    {{-- @dd($category) --}}
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->sku }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->urlKey }}</td>
                    <td>{{ $category->parent }}</td>
                    {{-- <td>{{ $product->category ?: 'ungroup' }}</td>
                    @isset($product->magentoId)
                        <td>
                            <a href="https://www.diepxuan.com/catalog/product/view/id/{{ $product->magentoId }}" target="_blank">
                                {{ $product->sku }}
                            </a>
                        </td>
                    @else
                        <td>
                            <form method="post" action="{{ route('catalog.store') }}" target="_blank" name="magento_form">
                                @method('POST') @csrf
                                <input type="hidden" value="magento2" name="type" />
                                <input type="hidden" value="{{ $product->sku }}" name="sku" />
                                <input type="hidden" value="{{ $product->name }}" name="name" />
                                <button type="submit" class="sync">Sync Magento</button>
                            </form>
                        </td>
                    @endisset --}}
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
