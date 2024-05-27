@extends('catalog::layouts.master')
@section('title', 'Nhóm hàng hoá vật tư')

@section('content')
    <style type="text/css">
    </style>
    <div class="pt-5">
        @include('catalog::category.category', ['categories' => $categories])
    </div>
@endsection
