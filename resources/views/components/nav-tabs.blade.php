@props(['defaultTab', 'tabs' => []])

<div x-data="{ activeTab: '{{ $defaultTab }}' }" x-on:switch-tab.window="activeTab = $event.detail[0]">
    <div class="mb-4 border-b border-gray-200 print:hidden">
        <ul class="-mb-px flex flex-wrap">
            @empty($tabs)
                {{ $nav }}
            @endempty
            {{-- @dd($tabs) --}}
            @foreach ($tabs as $tab)
                <li class="mr-2">
                    <a href="#" x-on:click.prevent="activeTab = '{{ $tab['key'] }}'"
                        :class="{
                            'border-blue-500 text-blue-600': activeTab === '{{ $tab['key'] }}',
                            'border-transparent hover:text-gray-600 hover:border-gray-300': activeTab !== '{{ $tab['key'] }}'
                        }"
                        class="inline-block rounded-t-lg border-b-2 p-4">
                        {{ $tab['label'] }}
                    </a>
                </li>
            @endforeach
            <li class="mr-2">
                <a href="#" class="inline-block cursor-default rounded-t-lg p-4">
                    <svg wire:loading class="text-blue -ml-1 mr-3 h-5 w-5 animate-spin"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </a>
            </li>
        </ul>
    </div>

    <div>
        @empty($tabs)
            {{ $content }}
        @endempty
        @foreach ($tabs as $tab)
            @isset(${$tab['key']})
                <div x-show="activeTab === '{{ $tab['key'] }}'" class="rounded-lg">
                    {{ ${$tab['key']} ?? '' }}
                </div>
            @endisset
        @endforeach
    </div>
</div>
