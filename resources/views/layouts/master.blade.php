<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Catalog Module - {{ config('app.name', 'Diepxuan') }}</title>

    <meta name="description" content="{{ $description ?? '' }}">
    <meta name="keywords" content="{{ $keywords ?? '' }}">
    <meta name="author" content="{{ $author ?? '' }}">

    <link rel="icon" type="image/x-icon"
        href="https://raw.githubusercontent.com/diepxuan/logo/main/output-onlinepngtools.png?token=GHSAT0AAAAAACQOJV5OC7PV6NLSSZ2DCMEOZSKWL7A">
    <link rel="shortcut icon" type="image/x-icon"
        href="https://raw.githubusercontent.com/diepxuan/logo/main/output-onlinepngtools.png?token=GHSAT0AAAAAACQOJV5OC7PV6NLSSZ2DCMEOZSKWL7A">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- Vite CSS --}}
    {{-- {{ module_vite('build-catalog', 'resources/assets/sass/app.scss') }} --}}
    <style type="text/css">
        body {
            font-size: 0.75rem
        }

        ul.menu {
            display: block;
            float: left;
            clear: left;
            width: 200px;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu ul {
            display: block;
            float: left;
            clear: both;
            list-style: none;
            padding: 0 0 0 10px;
            margin: 0;
            width: calc(100% - 10px);
        }

        .menu>li {
            display: block;
            float: left;
            clear: both;
            width: 100%;
            border-bottom: 1px solid rgb(172, 170, 170);
            /* margin: 0 0 -1px; */
            /* padding: 0; */
            height: auto;
        }

        .menu label {
            display: block;
            cursor: pointer;
            width: 100%;
        }

        .menu input[type=radio] {
            display: none;
        }

        .menu input+ul {
            display: none;
        }

        .menu input:checked+ul {
            display: block;
        }


        div.content {
            display: block;
            float: right;
            width: calc(100% - 230px);
        }
    </style>
</head>

<body>
    <ul class="menu">
        <li>
            @php
                $isCatalog = Request::is('catalog', 'catalog/*', 'category', 'category/*');
            @endphp
            <label for="htkbox">Hàng tồn kho</label>
            <input type="radio" id="htkbox" name="box" {{ $isCatalog ? 'checked' : '' }} />
            <ul>
                <li>
                    <a href={{ route('catalog.index') }}>
                        {{ 'Danh sách hàng hoá vật tư' }}
                    </a>
                </li>
                <li>
                    <a href={{ route('category.index') }}>
                        {{ 'Nhóm hàng hoá vật tư' }}
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <label for="sysbox">Hệ Thống</label>
            <input type="radio" id="sysbox" name="box" {{ Request::is('system') ? 'checked' : '' }} />
            <ul>
                <a href={{ route('system.index') }}>
                    {{ 'Dashboard' }}
                </a>
            </ul>
        </li>

    </ul>
    <div class="content">
        @yield('content')
    </div>

    {{-- Vite JS --}}
    {{-- {{ module_vite('build-catalog', 'resources/assets/js/app.js') }} --}}
</body>
