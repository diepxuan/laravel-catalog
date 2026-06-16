<x-catalog::head-title>{{ __('Dashboard') }}</x-catalog::head-title>
<x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ __('Dashboard') }}
    </h2>
</x-slot>

<div class="mx-4 my-2">
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        <h3 class="text-lg font-semibold text-gray-900">{{ __('Portal') }}</h3>
        <p class="mt-2 text-sm text-gray-600">
            {{ __('Menu điều hướng đã được cố định. Dùng thanh điều hướng để truy cập Simba ERP, Hệ thống và hồ sơ người dùng.') }}
        </p>
        <div class="mt-4 flex flex-wrap gap-3">
            <a href="{{ route('simba.index') }}" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                {{ __('Simba ERP') }}
            </a>
            <a href="{{ route('system.system') }}" class="rounded-md bg-gray-800 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                {{ __('Hệ thống') }}
            </a>
            @auth
                <a href="{{ route('profile.show') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    {{ __('Profile') }}
                </a>
            @endauth
        </div>
    </div>
</div>
