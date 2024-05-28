@extends('catalog::layouts.master')
@section('title', 'Nhóm hàng hoá vật tư')

@section('content')
    <style type="text/css">
    </style>
    @include('catalog::category.category', ['categories' => $categories])
@endsection
