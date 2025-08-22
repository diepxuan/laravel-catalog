<div class="ms-4 mt-1 bg-transparent" draggable="true" wire:dragover.prevent data-id="{{ $menu->id }}"
    x-show="dragging !== '{{ $menu->id }}'" @dragstart="dragStart($event)" {{-- @dragover="dragOver($event, {{ $menu->id }})"  --}}
    @dragend="dragEnd($event)">

    <div @dragenter="dragEnter($event, {{ $menu->id }})" @dragleave="dragLeave($event, {{ $menu->id }})"
        {{-- @drop="$wire.updateMenu($event, {{ $menu->id }})" --}} @drop="dragDrop($event, {{ $menu->id }})"
        class="flex items-center justify-between rounded-md border border-gray-300 px-3 py-0">
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

    @isset($menu->parent_id)
        <div x-show="dragOverId && dragOverId == {{ $menu->id }}"
            @drop="dragDrop($event, {{ $menu->parent_id }}, {{ $menu->id }})"
            class="mt-1 flex items-center justify-between rounded-md border border-blue-300 px-3 py-0">
            <span>{{ '' }} <small class="ps-3 text-gray-500">{{ '' }}</small></span>
        </div>
    @endisset
</div>
