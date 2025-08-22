<div>
    <x-head-title>{{ 'Menu - Hệ thống' }}</x-head-title>
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

    <div class="space-y-2 pt-2" x-data="menuTree($wire)" x-init="init()">
        @foreach ($rootIds as $id)
            @livewire('catalog::system.menu.item', ['menuId' => $id], key($id))
        @endforeach
    </div>

    <script>
        function menuTree($wire) {
            return {
                // tree: initialTree,
                dragging: null,
                dragOverId: null,
                placeholderPosition: null,
                init() {},

                dragStart(e) {
                    let el = e.target;
                    let id = el.dataset.id;
                    this.dragging = id;
                    console.log('Dragging element with id:', id);
                },

                dragOver(e, id) {
                    this.dragOverId = id;
                },

                dragEnd(e) {
                    this.dragging = null;
                    this.dragOverId = null;
                    this.placeholderPosition = null;

                    let el = e.target;
                    let id = el.dataset.id;
                    console.log('Drag ended element with id:', id);
                },

                dragEnter(e, id) {
                    if (!this.dragging || id == this.dragging) return;
                    this.dragOverId = id;
                    console.log('Dragging ' + this.dragging + ' over ' + id);
                },

                dragLeave(e, id) {
                    if (!this.dragging || id == this.dragging) return;
                    this.dragOverId = id;
                    console.log('Dragging ' + this.dragging + ' out ' + id);
                },

                dragDrop(e, parentId, preId) {
                    console.log('Dropped ' + this.dragging + ' on ' + parentId + ' at order after ' + preId);
                    $wire.updateMenu(this.dragging, parentId, preId);
                    this.dragOverId = null;
                    this.dragging = null;
                }
            }
        }
    </script>
</div>
