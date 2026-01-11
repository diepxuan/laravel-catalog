<div class="ms-4 mt-1 bg-transparent">

    <div class="flex items-center justify-between rounded-md border border-gray-300 px-3 py-0">
        <span>
            <strong class="text-xs text-gray-700">{{ $menu->id }}</strong>
            @if ($menu->route == 'space')
                <span class="text-xs text-gray-500">{{ $menu->name }}</span>
            @else
                {{ $menu->name }}
            @endif

        </span>
        <span>
            <small class="px-1 text-gray-500">{{ $menu->route ?? 'N/A' }}</small>
            <select class="rounded-md border-0 p-0 text-xs" wire:model="parentId" wire:change="updateMenu">
                <option value="">---</option>
                @include('catalog::system.menu.option-tree', ['items' => $availableParents])
            </select>
            <small class="px-1 text-gray-500">{{ $menu->order ?? 0 }}</small>
            <button wire:click="deleteMenu({{ $menu->id }})" class="text-red-500">x</button>
        </span>
    </div>
    @foreach ($childIds as $id)
        @livewire('catalog::system.menu.item', ['menuId' => $id], key("menu-item-$id"))
    @endforeach
</div>
