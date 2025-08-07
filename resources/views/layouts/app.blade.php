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

    <title>@stack('title', config('app.name', 'Laravel'))</title>

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
        <header class="bg-white shadow print:hidden">
            <div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
                @if (isset($header))
                    {{ $header }}
                @endif
            </div>
            <!-- System information -->
            <div class="mx-auto max-w-7xl px-4 pb-6 sm:px-6 lg:px-8">
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

            <div class="py-12 print:py-0">
                <div class="max-w-12xl mx-auto sm:px-6 lg:px-8 print:px-0">
                    <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg print:rounded-none print:shadow-none">
                        <div class="border-b border-gray-200 bg-white p-6 lg:p-8 print:border-0 print:p-0">
                            {{ $slot ?? '' }}
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
