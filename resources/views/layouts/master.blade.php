<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@php
    $logo = 'https://raw.githubusercontent.com/diepxuan/logo/main/icons/brand/MetallicBrown.svg';
    $favicon = 'https://raw.githubusercontent.com/diepxuan/logo/main/icons/favicons/MetallicBrown.svg';
    $brand = 'https://raw.githubusercontent.com/diepxuan/logo/main/icons/texts/MetallicBrown.svg';
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style type="text/css">
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row justify-content-between">
            <div class="col-md-3">
                @include('catalog::layouts.menu')
            </div>

            <div class="col-md-9 pt-5">
                @yield('content')
            </div>
        </div>
    </div>

    {{-- Vite JS --}}
    {{-- {{ module_vite('build-catalog', 'resources/assets/js/app.js') }} --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>
