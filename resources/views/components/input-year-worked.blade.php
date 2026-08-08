<div x-data="{ open: @entangle('open') }" class="relative inline-flex">
    <button
        type="button"
        wire:click="toggle"
        class="inline-flex items-center rounded border border-transparent px-1.5 py-0.5 text-xs text-gray-500 transition hover:border-gray-300 hover:bg-gray-100 hover:text-gray-800"
        title="{{ __('Chọn năm làm việc') }}"
    >
        [ {{ $selectedYear }} ]
    </button>

    <div
        x-show="open"
        x-cloak
        @click.outside="open = false; $wire.close()"
        class="absolute left-0 top-full z-50 mt-1 w-52 rounded-lg border border-gray-200 bg-white p-2 shadow-lg"
    >
        <div class="max-h-64 overflow-y-auto">
            <div class="grid grid-cols-3 gap-1">
                @foreach ($years as $year)
                    <button
                        type="button"
                        wire:click="selectYear({{ $year }})"
                        class="rounded border px-2 py-1.5 text-center text-xs font-medium transition {{ $selectedYear === $year ? 'border-blue-500 bg-blue-50 text-blue-800' : 'border-gray-200 bg-white text-gray-700 hover:bg-gray-50' }}"
                    >
                        {{ $year }}
                    </button>
                @endforeach
            </div>
        </div>

        @error('selectedYear')
            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>
