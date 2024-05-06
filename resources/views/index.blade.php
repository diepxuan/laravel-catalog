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
    </style>
    <table class="table table-hover table-condensed table-sm text-monospace small">
        <tbody>
            @foreach ($products as $product)
                {{-- @dd($product) --}}
                <tr id="{{ "$product->ma_cty|$product->ma_vt" }}">
                    <td>{{ $product->ma_vt }}</td>
                    <td>{{ $product->ten_vt }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
