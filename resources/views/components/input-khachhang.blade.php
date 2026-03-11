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
            class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 shadow-sm transition-all placeholder:text-gray-400 hover:border-gray-300 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
        />

        {{-- Clear button --}}
        @if($value)
            <button
                type="button"
                @click="$wire.clear()"
                class="absolute right-3 top-1/2 -translate-y-1/2 rounded-full p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        @endif

        {{-- Search icon --}}
        <div class="absolute right-3 top-1/2 -translate-y-1/2" wire:loading.remove wire:target="updatedSearch">
            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>

        {{-- Loading spinner --}}
        <div wire:loading wire:target="updatedSearch" class="absolute right-3 top-1/2 -translate-y-1/2">
            <svg class="h-4 w-4 animate-spin text-indigo-500" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
            </svg>
        </div>
    </div>

    {{-- Dropdown results --}}
    @if($showDropdown && count($results) > 0)
        <div class="absolute z-50 mt-1 w-full overflow-hidden rounded-lg border border-gray-200 bg-white shadow-lg">
            <ul class="max-h-64 overflow-y-auto">
                @foreach($results as $kh)
                    <li
                        wire:click="selectCustomer('{{ $kh['ma_kh'] }}', '{{ addslashes($kh['ten_kh']) }}')"
                        class="cursor-pointer border-b border-gray-100 px-4 py-2.5 transition-colors last:border-b-0 hover:bg-indigo-50"
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
        <div class="absolute z-50 mt-1 w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-center text-sm text-gray-500 shadow-lg">
            Không tìm thấy khách hàng
        </div>
    @endif
</div>
