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

    <x-catalog::head-title>
        {{ $pageTitle ?? config('app.name', 'Laravel') }}
    </x-catalog::head-title>

    <link rel="icon" type="image/x-icon" href="{{ $favicon }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ $favicon }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased">
    <x-banner />

    <div class="min-h-screen bg-gray-100">
        @livewire('catalog::navigation-menu')

        <!-- Page Heading -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto pt-6 px-4 sm:px-6 lg:px-8">
                @if (isset($header))
                    {{ $header }}
                @endif
            </div>
            <!-- System information -->
            <div class="max-w-7xl mx-auto pb-6 px-4 sm:px-6 lg:px-8">
                <div class="text-xs text-gray-500">
                    {{-- <x-catalog::sys-language /> --}}
                    <x-sys-user-info />
                    <x-sys-year />
                    <x-sys-company />
                    <x-sys-language />
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main>

            <div class="py-12">
                <div class="max-w-12xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    @stack('modals')

    @livewireScripts
</body>

</html>
