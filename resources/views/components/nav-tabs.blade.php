@props(['defaultTab'])

<div x-data="{ activeTab: '{{ $defaultTab }}' }" x-on:switch-tab.window="activeTab = $event.detail[0]">
    <div class="mb-4 border-b border-gray-200">
        <ul class="-mb-px flex flex-wrap">
            {{ $nav }}
        </ul>
    </div>

    <div>
        {{ $content }}
    </div>
</div>
