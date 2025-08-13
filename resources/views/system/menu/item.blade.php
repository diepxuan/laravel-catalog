<div class="ms-4 mt-1 bg-transparent" draggable="true" wire:dragover.prevent data-id="{{ $menu->id }}"
    x-show="dragging !== '{{ $menu->id }}'" @dragstart="dragStart($event)"
    @dragenter="dragEnter($event, {{ $menu->id }})" @dragleave="dragLeave($event, {{ $menu->id }})"
    @drop="dragDrop($event, {{ $menu->id }})">

    <div x-show="dragOverId && dragOverId == {{ $menu->id }}"
        class="my-2 flex items-center justify-between rounded-md border border-blue-300 px-3 py-1">
        <span>{{ '' }} <small class="ps-3 text-gray-500">{{ '' }}</small></span>
    </div>

    <div class="flex items-center justify-between rounded-md border border-gray-300 px-3 py-1">
        <span>
            <bold class="text-xs text-gray-700">{{ $menu->id }}.{{ $menu->order }}</bold>
            {{ $menu->name }}
            <small class="ps-3 text-gray-500">{{ $menu->route ?? 'N/A' }}</small>
        </span>
        <button wire:click="deleteMenu({{ $menu->id }})" class="text-red-500">x</button>
    </div>
    @foreach ($childIds as $id)
        @livewire('catalog::system.menu.item', ['menuId' => $id], key($id))
    @endforeach

    <div x-show="dragOverId && dragOverId == {{ $menu->id }}"
        class="my-2 flex items-center justify-between rounded-md border border-blue-300 px-3 py-1">
        <span>{{ '' }} <small class="ps-3 text-gray-500">{{ '' }}</small></span>
    </div>
</div>
