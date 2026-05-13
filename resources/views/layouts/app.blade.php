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
    @stack('styles')
</head>

<body class="font-sans text-sm antialiased">
    <!-- Loading Overlay -->
    <div id="catalog-loading-overlay" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
        <div class="flex flex-col items-center">
            <svg class="h-16 w-16 animate-spin text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="mt-4 text-lg font-semibold text-white">Đang xử lý...</p>
        </div>
    </div>

    <x-banner />

    <x-catalog::toast />

    <div class="min-h-screen bg-gray-100">
        @livewire('catalog::navigation-menu')

        <!-- Page Heading -->
        <header class="border-b border-gray-200 bg-white shadow print:hidden">
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

            <div class="py-0">
                <div class="max-w-12xl mx-auto px-0">
                    <div class="overflow-hidden bg-white">
                        <div class="border-b border-gray-200 bg-white px-6 py-3 lg:p-8 print:border-0 print:p-0">
                            {{ $slot ?? '' }}
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    @stack('modals')
    <!-- Scripts Stack -->
    @stack('scripts')
    @livewireScripts

    <!-- Loading Overlay Script -->
    <script>
        // Show loading overlay when Livewire request starts
        document.addEventListener('livewire:init', () => {
            const overlay = document.getElementById('catalog-loading-overlay');

            Livewire.hook('request', ({ respond }) => {
                // Show overlay
                overlay.classList.remove('hidden');

                respond(({ status, preventDefault }) => {
                    // Hide overlay when response received
                    overlay.classList.add('hidden');
                });
            });
        });
    </script>
</body>

</html>
