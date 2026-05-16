<div class="w-full">
    <x-head-title>{{ __('Chọn năm làm việc') }}</x-head-title>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Chọn năm làm việc') }}
            </h2>
            <p class="text-sm text-gray-600">
                {{ __('Menu 90.30.02') }}
            </p>
        </div>
    </x-slot>

    <div class="space-y-4">
        @if ($statusMessage)
            <div class="rounded-md border border-green-200 bg-green-50 p-3 text-sm text-green-800">
                {{ $statusMessage }}
            </div>
        @endif

        @error('selectedYear')
            <div class="rounded-md border border-red-200 bg-red-50 p-3 text-sm text-red-800">
                {{ $message }}
            </div>
        @enderror

        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8">
            @foreach ($years as $year)
                <button
                    type="button"
                    wire:click="selectYear({{ $year }})"
                    class="rounded-md border px-4 py-3 text-center text-sm font-medium {{ $selectedYear === $year ? 'border-blue-500 bg-blue-50 text-blue-800' : 'border-gray-200 bg-white text-gray-800 hover:bg-gray-50' }}"
                >
                    {{ $year }}
                </button>
            @endforeach
        </div>
    </div>
</div>
