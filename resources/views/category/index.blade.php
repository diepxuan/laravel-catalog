<?php
use Diepxuan\Core\Models\Package;
?>
@extends('catalog::layouts.master')
@section('title', 'Nhóm hàng hoá vật tư')

@section('content')
    @include('catalog::category.category', ['categories' => $categories])
    @vite(['diepxuan/laravel-catalog/resources/css/app.css', 'diepxuan/laravel-catalog/resources/js/app.js'])
@endsection
