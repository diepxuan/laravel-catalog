@extends('catalog::layouts.master')

@section('content')
    <style>
        body {
            font-size: 0.75rem
        }

        ul {
            clear: both;
            display: block;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        ul li {
            display: block;
            float: left;
            padding: 0 4px;
            margin: 0;
            line-height: 1.2rem;
        }

        ul.childs>li:first-child {
            display: block;
            width: 12px;
            height: 1.2rem;
        }
    </style>
    <table class="table table-hover table-condensed table-sm text-monospace small">
        <tbody>
            @include('catalog::category.category', ['categories' => $categories])
        </tbody>
    </table>
@endsection
