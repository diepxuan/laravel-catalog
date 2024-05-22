<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@php
    $logo = 'https://raw.githubusercontent.com/diepxuan/logo/main/icons/MetallicBrown.svg';
    $favicon = 'https://raw.githubusercontent.com/diepxuan/logo/main/favicons/MetallicBrown.svg';
    $brand = 'https://raw.githubusercontent.com/diepxuan/logo/main/texts/MetallicBrown.svg';
@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>@yield('title', 'Catalog') - {{ config('app.name', 'Diepxuan') }}</title>

    <meta name="description" content="{{ $description ?? '' }}">
    <meta name="keywords" content="{{ $keywords ?? '' }}">
    <meta name="author" content="{{ $author ?? '' }}">

    <link rel="icon" type="image/x-icon" href="{{ $favicon }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ $favicon }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- Vite CSS --}}
    {{-- {{ module_vite('build-catalog', 'resources/assets/sass/app.scss') }} --}}
    <style type="text/css">
        body {
            font-size: 0.75rem
        }

        div.content {
            display: block;
            float: right;
            width: calc(100% - 230px);
        }
    </style>
</head>

<body>
    @include('catalog::layouts.menu')
    <div class="content">
        @yield('content')
    </div>

    {{-- Vite JS --}}
    {{-- {{ module_vite('build-catalog', 'resources/assets/js/app.js') }} --}}
</body>
