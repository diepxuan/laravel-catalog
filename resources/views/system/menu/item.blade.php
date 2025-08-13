<div class="ps-4" draggable="true" {{-- wire:dragstart="startDrag({{ $menu->id }})"  --}} wire:dragover.prevent>
    <div class="mt-0.5">
        <div class="flex items-center justify-between rounded-md border border-gray-300 px-3 py-1">
            <span>{{ $menu->name }} <small class="ps-3 text-gray-500">{{ $menu->route ?? 'N/A' }}</small></span>
            <button wire:click="deleteMenu({{ $menu->id }})" class="text-red-500">x</button>
        </div>
    </div>
    @foreach ($childIds as $id)
        @livewire('catalog::system.menu.item', ['menuId' => $id], key($id))
    @endforeach
</div>
