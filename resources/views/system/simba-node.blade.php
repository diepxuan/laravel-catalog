@php
    $depth = $item->depth;
@endphp

<div class="group flex items-center gap-1.5 border-b border-gray-100 px-3 py-1.5 text-xs transition hover:bg-gray-50"
     style="padding-left: {{ $depth * 16 + 8 }}px;"
     x-show="isVisible('{{ addslashes($item->menuid) }}')">

    {{-- Collapse chevron (only for nodes with children) --}}
    @if ($item->hasChildren)
        <button type="button"
                class="flex h-4 w-4 flex-shrink-0 items-center justify-center text-gray-400 hover:text-gray-600"
                @click.stop="toggleCollapse('{{ addslashes($item->menuid) }}')">
            <svg class="h-3 w-3 transition-transform duration-150"
                 :class="{ 'rotate-90': !isCollapsed('{{ addslashes($item->menuid) }}') }"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    @else
        <div class="w-4 flex-shrink-0"></div>
    @endif

    {{-- Icon by type --}}
    @if ($item->isRoot)
        <svg class="h-4 w-4 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
    @elseif ($item->isGroup)
        <svg class="h-4 w-4 flex-shrink-0 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
        </svg>
    @elseif ($item->isVoucher)
        <svg class="h-4 w-4 flex-shrink-0 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
    @elseif ($item->isMasterData)
        <svg class="h-4 w-4 flex-shrink-0 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
        </svg>
    @elseif ($item->isReport)
        <svg class="h-4 w-4 flex-shrink-0 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
        </svg>
    @elseif ($item->isSetup)
        <svg class="h-4 w-4 flex-shrink-0 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
    @else
        <svg class="h-4 w-4 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
    @endif

    {{-- Name --}}
    @if ($item->portalUrl ?? false)
        <a href="{{ $item->portalUrl }}"
           class="min-w-0 flex-1 truncate text-blue-700 hover:text-blue-900 hover:underline"
           title="{{ $item->portalRoute }}: {{ $item->name ?: $item->menuid }}">
            {{ $item->name ?: $item->menuid }}
        </a>
        <span class="hidden flex-shrink-0 rounded bg-emerald-50 px-1.5 py-0.5 text-[10px] font-medium text-emerald-700 ring-1 ring-inset ring-emerald-200 sm:inline"
              title="{{ $item->portalRoute }}">
            {{ $item->portalLabel ?? 'Portal' }}
        </span>
    @else
        <span class="min-w-0 flex-1 truncate text-gray-800" title="{{ $item->name ?: $item->menuid }}">
            {{ $item->name ?: $item->menuid }}
        </span>
    @endif

    {{-- Menu ID (right-aligned, clickable in selection mode if available) --}}
    <span
        class="flex-shrink-0 rounded px-1 font-mono text-[10px] transition"
        :class="{
            'text-gray-400 cursor-default': !simbaSelectMode && !{{ $item->isAlreadyUsed ? 'true' : 'false' }},
            'pulse-green-simba cursor-pointer': simbaSelectMode && !{{ $item->isAlreadyUsed ? 'true' : 'false' }},
            'text-gray-300 line-through cursor-not-allowed': {{ $item->isAlreadyUsed ? 'true' : 'false' }}
        }"
        @click="selectMenuId('{{ addslashes($item->menuid) }}')"
        title="{{ $item->isAlreadyUsed ? 'Đã sử dụng trong Portal' : 'Click để chọn' }}: {{ $item->menuid }}">
        {{ $item->menuid }}
    </span>

    {{-- DLL badge --}}
    @if ($item->dllName)
        <span class="hidden flex-shrink-0 rounded bg-gray-100 px-1.5 py-0.5 font-mono text-[10px] text-gray-500 group-hover:block" title="{{ $item->dllName }}">
            {{ Str::limit($item->dllName, 6) }}
        </span>
    @endif
</div>
