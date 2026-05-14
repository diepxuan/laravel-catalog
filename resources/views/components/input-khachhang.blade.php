<div class="relative w-full" x-data="{
    showDropdown: @entangle('showDropdown'),
    searching: @entangle('searching')
}" @click.outside="showDropdown = false">
    {{-- Input field --}}
    <div class="relative">
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            @focus="showDropdown = true"
            @blur="setTimeout(() => showDropdown = false, 200)"
            placeholder="{{ $placeholder }}"
            class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-xs text-gray-700 shadow-sm transition-all placeholder:text-gray-400 hover:border-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
        />

        {{-- Search & loading icons --}}
        <div class="absolute right-2 top-1/2 -translate-y-1/2 flex items-center gap-1" wire:loading.remove wire:target="updatedSearch">
            @if($value)
                <button
                    type="button"
                    @click="$wire.clear()"
                    class="rounded-full p-0.5 text-gray-400 hover:text-gray-600"
                >
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            @endif
            <svg class="h-3.5 w-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>

        {{-- Loading spinner --}}
        <div wire:loading wire:target="updatedSearch" class="absolute right-2 top-1/2 -translate-y-1/2">
            <svg class="h-3.5 w-3.5 animate-spin text-blue-500" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
            </svg>
        </div>
    </div>

    {{-- Dropdown results --}}
    @if($showDropdown && count($results) > 0)
        <div class="absolute z-50 mt-1 w-full overflow-hidden rounded-md border border-gray-300 bg-white shadow-lg">
            <ul class="max-h-64 overflow-y-auto">
                @foreach($results as $kh)
                    <li
                        wire:click="selectCustomer('{{ $kh['ma_kh'] }}', '{{ addslashes($kh['ten_kh']) }}')"
                        class="cursor-pointer border-b border-gray-100 px-3 py-2 transition-colors last:border-b-0 hover:bg-blue-50"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="font-medium text-gray-800">{{ $kh['ten_kh'] }}</p>
                                <p class="text-xs text-gray-500">{{ $kh['dia_chi'] }} {{ $kh['tel'] ? '• ' . $kh['tel'] : '' }}</p>
                            </div>
                            <span class="rounded bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600">
                                {{ $kh['ma_kh'] }}
                            </span>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- No results --}}
    @if($showDropdown && !$searching && count($results) === 0 && strlen(trim($search)) > 0)
        <div class="absolute z-50 mt-1 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-center text-xs text-gray-500 shadow-lg">
            Không tìm thấy
        </div>
    @endif
</div>
