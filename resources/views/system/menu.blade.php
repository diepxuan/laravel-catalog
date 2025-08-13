<div>
    <x-head-title>{{ 'Hệ thống' }}</x-head-title>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ 'Điều hướng' }}
        </h2>
        <p>{{ 'Navigation Menu' }}</p>
    </x-slot>

    <form wire:submit.prevent="addMenu">
        <div class="items-center gap-2">
            <input class="rounded-md border-gray-300 py-1 shadow-sm" wire:model="newMenu.name" placeholder="Tên menu" />
            <input class="rounded-md border-gray-300 py-1 shadow-sm" wire:model="newMenu.route" placeholder="Đường dẫn" />
            <div class="inline-flex items-center">
                <x-button-loading class="rounded-md bg-blue-600 px-2 py-1 text-white hover:bg-blue-700" type="submit">
                    Thêm
                </x-button-loading>
            </div>
        </div>
    </form>

    <div class="space-y-2 pt-2">
        @foreach ($rootIds as $id)
            @livewire('catalog::system.menu.item', ['menuId' => $id], key($id))
        @endforeach
    </div>
</div>
