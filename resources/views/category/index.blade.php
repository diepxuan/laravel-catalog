@extends('catalog::layouts.master')
@section('title', 'Nhóm hàng hoá vật tư')

@section('content')
    <style type="text/css">
        .content ul {
            clear: both;
            display: block;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .content ul li {
            display: block;
            float: left;
            padding: 0 4px;
            margin: 0;
            line-height: 1.2rem;
        }

        .content ul.childs>li:first-child {
            display: block;
            width: 12px;
            height: 1.2rem;
        }
    </style>
    @include('catalog::category.category', ['categories' => $categories])
@endsection
