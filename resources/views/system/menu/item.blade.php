<div class="ms-4 mt-1 bg-transparent" draggable="true" wire:dragover.prevent data-id="{{ $menu->id }}"
    @dragstart="dragStart($event)" @dragenter="dragEnter($event, {{ $menu->id }})"
    @dragleave="dragLeave($event, {{ $menu->id }})"
    :class="dragOverId == {{ $menu->id }} ? 'bg-blue-100 border border-blue-400' : ''">
    <div class="flex items-center justify-between rounded-md border border-gray-300 px-3 py-1">
        <span>{{ $menu->name }} <small class="ps-3 text-gray-500">{{ $menu->route ?? 'N/A' }}</small></span>
        <button wire:click="deleteMenu({{ $menu->id }})" class="text-red-500">x</button>
    </div>
    @foreach ($childIds as $id)
        @livewire('catalog::system.menu.item', ['menuId' => $id], key($id))
    @endforeach
</div>
