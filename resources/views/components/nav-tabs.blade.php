@props(['defaultTab'])

<div x-data="{ activeTab: '{{ $defaultTab }}' }" x-on:switch-tab.window="activeTab = $event.detail[0]">
    <div class="mb-4 border-b border-gray-200">
        <ul class="-mb-px flex flex-wrap">
            {{ $nav }}
            <li class="mr-2">
                <a href="#" class="inline-block rounded-t-lg p-4 cursor-default">
                    <svg wire:loading class="-ml-1 mr-3 h-5 w-5 animate-spin text-blue" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
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
        {{ $content }}
    </div>
</div>
