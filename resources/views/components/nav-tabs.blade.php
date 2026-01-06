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
                    <x-icons.loadding />
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
