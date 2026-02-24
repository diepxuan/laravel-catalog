<div class="space-y-6" x-data="{
    errorMessage: null,
    showError(message) {
        this.errorMessage = message;
        setTimeout(() => {
            this.errorMessage = null;
        }, 5000);
    },
    init() {
        // Clear highlight after delay
        Livewire.on('clear-highlight', (event) => {
            setTimeout(() => {
                this.$wire.set('recentlyUpdated.' + event.nodeId, false);
            }, 2000);
        });
        
        // Error notification
        Livewire.on('show-error', (event) => {
            this.showError(event.message);
        });
    }
}">
    <x-head-title>{{ 'Quản lý Menu' }}</x-head-title>
    
    <!-- Error Notification -->
    <div x-show="errorMessage" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="rounded-lg border border-red-300 bg-red-50 p-4 text-red-800">
        <div class="flex items-center">
            <svg class="mr-3 h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span x-text="errorMessage"></span>
        </div>
    </div>
    
    <!-- Add Menu Form -->
    <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
        <h3 class="mb-3 text-lg font-semibold text-gray-800">Thêm menu mới</h3>
        
        <form wire:submit.prevent="addMenu" class="space-y-3">
            <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Tên menu</label>
                    <input type="text" 
                           class="w-full rounded-md border-gray-300 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           wire:model="newMenu.name" 
                           placeholder="Ví dụ: Hệ thống" 
                           required />
                </div>
                
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Đường dẫn (route)</label>
                    <input type="text" 
                           class="w-full rounded-md border-gray-300 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           wire:model="newMenu.route" 
                           placeholder="Ví dụ: system.menu" />
                </div>
                
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Menu cha</label>
                    <select class="w-full rounded-md border-gray-300 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            wire:model="newMenu.parent_id">
                        <option value="">-- Không có (menu gốc) --</option>
                        @foreach ($this->parentOptions as $menu)
                            <option value="{{ $menu->id }}">
                                {{ $menu->name }} @if($menu->route) ({{ $menu->route }}) @endif
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" 
                        class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Thêm menu
                </button>
            </div>
        </form>
    </div>
    
    <!-- Tree Container -->
    <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
        <!-- Tree Header -->
        <div class="border-b border-gray-200 bg-gray-50 px-4 py-3">
            <div class="grid grid-cols-12 gap-4 text-sm font-medium text-gray-700">
                <div class="col-span-5">Tên menu</div>
                <div class="col-span-4">Route</div>
                <div class="col-span-1 text-center">ID</div>
                <div class="col-span-2 text-center">Thứ tự</div>
            </div>
        </div>

        <!-- Tree Body -->
        <div class="min-h-[200px] p-2"
             @dragover.prevent="
                if (!$wire.draggingNodeId) return;
                event.dataTransfer.dropEffect = 'move';
             "
             @drop.prevent="$wire.handleDrop()">
            
            @if($nodes->isEmpty())
                <div class="flex flex-col items-center justify-center py-12 text-gray-500">
                    <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <p class="mt-2">Chưa có menu nào</p>
                </div>
            @else
                <!-- Single loop for all nodes -->
                @foreach($nodes as $node)
                    @if($this->isVisible($node->id))
                        @php
                            $nodeId = $node->id;
                            $isEditing = $this->editingNodeId === $nodeId;
                            $isDragging = $this->draggingNodeId === $nodeId;
                            $isDropTarget = $this->dropTargetId === $nodeId;
                            $dropPosition = $this->dropPosition;
                            $isRecentlyUpdated = $this->recentlyUpdated[$nodeId] ?? false;
                            $isSavingNode = $this->nodeSaving[$nodeId] ?? false;
                            $hasChildren = $node->has_children;
                            $isExpanded = $this->isExpanded($nodeId);
                        @endphp

                        <!-- Drop Zone Before -->
                        @if(!$isDragging)
                        <div class="my-1"
                             @dragover="if ($wire.draggingNodeId && $wire.draggingNodeId !== {{ $nodeId }}) { 
                                 $wire.setDropTarget({{ $nodeId }}, 'before'); 
                                 event.dataTransfer.dropEffect = 'move';
                             }"
                             @dragleave="if ($wire.dropTargetId === {{ $nodeId }} && $wire.dropPosition === 'before') { 
                                 $wire.clearDropTarget(); 
                             }"
                             :class="{ 'border-t-2 border-blue-400': $wire.dropTargetId === {{ $nodeId }} && $wire.dropPosition === 'before' }">
                        </div>
                        @endif

                        <!-- Node Container -->
                        <div class="group relative flex items-center rounded-lg border transition-all duration-200 hover:border-gray-300 hover:bg-gray-50 {{ $isRecentlyUpdated ? 'border-green-300 bg-green-50' : 'border-gray-200' }} {{ $isDragging ? 'opacity-50' : '' }} {{ $isDropTarget && $dropPosition === 'inside' ? 'border-2 border-dashed border-blue-400 bg-blue-50' : '' }}"
                             draggable="true"
                             @dragstart="$wire.startDrag({{ $nodeId }})"
                             @dragend="$wire.clearDragState()"
                             @dragover="if ($wire.draggingNodeId && $wire.draggingNodeId !== {{ $nodeId }} && $hasChildren) { 
                                 $wire.setDropTarget({{ $nodeId }}, 'inside'); 
                                 event.dataTransfer.dropEffect = 'move';
                             }"
                             @dragleave="if ($wire.dropTargetId === {{ $nodeId }} && $wire.dropPosition === 'inside') { 
                                 $wire.clearDropTarget(); 
                             }"
                             style="padding-left: {{ $node->level * 24 }}px">
                            
                            <!-- Expand/Collapse Toggle -->
                            @if($hasChildren)
                                <button type="button"
                                        class="ml-2 flex h-6 w-6 items-center justify-center text-gray-500 hover:text-gray-700 {{ $isDropTarget && $dropPosition === 'inside' ? 'text-blue-500' : '' }}"
                                        wire:click="toggleNode({{ $nodeId }})">
                                    <svg class="h-4 w-4 transition-transform duration-200 {{ $isExpanded ? 'rotate-90' : '' }}" 
                                         fill="none" 
                                         stroke="currentColor" 
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            @else
                                <div class="ml-8 w-2"></div> <!-- Spacer for alignment -->
                            @endif

                            <!-- Drag Handle -->
                            <div class="ml-1 cursor-move text-gray-400 hover:text-gray-600"
                                 title="Kéo để di chuyển">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                                </svg>
                            </div>

                            <!-- Node Content -->
                            <div class="flex flex-1 items-center px-3 py-2">
                                <!-- Name Column -->
                                <div class="col-span-5 w-5/12">
                                    @if($isEditing)
                                        <div class="flex items-center space-x-2">
                                            <input type="text"
                                                   class="w-full rounded-md border-gray-300 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                   wire:model="editingName"
                                                   wire:keydown.enter="saveEdit"
                                                   wire:keydown.escape="cancelEdit"
                                                   autofocus />
                                            
                                            <button type="button"
                                                    class="rounded-md bg-green-100 p-1 text-green-700 hover:bg-green-200"
                                                    wire:click="saveEdit"
                                                    wire:loading.attr="disabled"
                                                    wire:target="saveEdit">
                                                @if($isSavingNode)
                                                    <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                @else
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                @endif
                                            </button>
                                            
                                            <button type="button"
                                                    class="rounded-md bg-gray-100 p-1 text-gray-700 hover:bg-gray-200"
                                                    wire:click="cancelEdit">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    @else
                                        <button type="button"
                                                class="text-left font-medium text-gray-900 hover:text-blue-600"
                                                wire:click="startEdit({{ $nodeId }})">
                                            {{ $node->name }}
                                        </button>
                                    @endif
                                </div>

                                <!-- Route Column -->
                                <div class="col-span-4 w-4/12">
                                    @if($isEditing)
                                        <input type="text"
                                               class="w-full rounded-md border-gray-300 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                               wire:model="editingRoute"
                                               wire:keydown.enter="saveEdit"
                                               wire:keydown.escape="cancelEdit"
                                               placeholder="route.name" />
                                    @else
                                        <span class="text-sm text-gray-600">
                                            {{ $node->route ?? '—' }}
                                        </span>
                                    @endif
                                </div>

                                <!-- ID Column -->
                                <div class="col-span-1 w-16 text-center">
                                    <span class="rounded-full bg-gray-100 px-2 py-1 text-xs font-mono text-gray-700">
                                        {{ $nodeId }}
                                    </span>
                                </div>

                                <!-- Order Column -->
                                <div class="col-span-1 w-24 text-center">
                                    <div class="flex items-center justify-center space-x-1">
                                        @php
                                            $canMoveUp = $this->canMoveUp($nodeId);
                                            $canMoveDown = $this->canMoveDown($nodeId);
                                            $isSavingOrder = $this->nodeSaving[$nodeId] ?? false;
                                        @endphp
                                        
                                        <button type="button"
                                                class="rounded-md p-1 text-gray-500 hover:bg-gray-100 hover:text-gray-700 {{ !$canMoveUp ? 'opacity-30 cursor-not-allowed' : '' }}"
                                                wire:click="reorderMenu({{ $nodeId }}, 'up')"
                                                title="Di chuyển lên"
                                                {{ !$canMoveUp ? 'disabled' : '' }}
                                                wire:loading.attr="disabled">
                                            @if($isSavingOrder)
                                                <svg class="h-3 w-3 animate-spin" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            @else
                                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                                </svg>
                                            @endif
                                        </button>
                                        
                                        <span class="text-sm font-medium text-gray-700 min-w-[20px]">
                                            {{ $node->order }}
                                        </span>
                                        
                                        <button type="button"
                                                class="rounded-md p-1 text-gray-500 hover:bg-gray-100 hover:text-gray-700 {{ !$canMoveDown ? 'opacity-30 cursor-not-allowed' : '' }}"
                                                wire:click="reorderMenu({{ $nodeId }}, 'down')"
                                                title="Di chuyển xuống"
                                                {{ !$canMoveDown ? 'disabled' : '' }}
                                                wire:loading.attr="disabled">
                                            @if($isSavingOrder)
                                                <svg class="h-3 w-3 animate-spin" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            @else
                                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            @endif
                                        </button>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="ml-4 flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    @if(!$isEditing)
                                        <button type="button"
                                                class="rounded-md p-1 text-gray-500 hover:bg-gray-100 hover:text-gray-700"
                                                wire:click="startEdit({{ $nodeId }})"
                                                title="Chỉnh sửa">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                    @endif
                                    
                                    <button type="button"
                                            class="rounded-md p-1 text-red-500 hover:bg-red-100 hover:text-red-700"
                                            wire:click="deleteNode({{ $nodeId }})"
                                            title="Xóa"
                                            wire:loading.attr="disabled">
                                        @if($isSavingNode)
                                            <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        @else
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        @endif
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Drop Zone After -->
                        @if(!$isDragging)
                        <div class="my-1"
                             @dragover="if ($wire.draggingNodeId && $wire.draggingNodeId !== {{ $nodeId }}) { 
                                 $wire.setDropTarget({{ $nodeId }}, 'after'); 
                                 event.dataTransfer.dropEffect = 'move';
                             }"
                             @dragleave="if ($wire.dropTargetId === {{ $nodeId }} && $wire.dropPosition === 'after') { 
                                 $wire.clearDropTarget(); 
                             }"
                             :class="{ 'border-t-2 border-blue-400': $wire.dropTargetId === {{ $nodeId }} && $wire.dropPosition === 'after' }">
                        </div>
                        @endif

                        <!-- Drop Zone Inside (children area) -->
                        @if($hasChildren && $isExpanded && !$isDragging)
                        <div class="ml-{{ $node->level * 6 + 8 }} my-1 rounded-lg border-2 border-dashed border-blue-300 bg-blue-50 p-2 transition-all duration-200"
                             @dragover="if ($wire.draggingNodeId && $wire.draggingNodeId !== {{ $nodeId }}) { 
                                 $wire.setDropTarget({{ $nodeId }}, 'inside'); 
                                 event.dataTransfer.dropEffect = 'move';
                             }"
                             @dragleave="if ($wire.dropTargetId === {{ $nodeId }} && $wire.dropPosition === 'inside') { 
                                 $wire.clearDropTarget(); 
                             }"
                             :class="{ 'opacity-100': $wire.dropTargetId === {{ $nodeId }} && $wire.dropPosition === 'inside', 'opacity-0': !($wire.dropTargetId === {{ $nodeId }} && $wire.dropPosition === 'inside') }">
                            <div class="text-center text-sm text-blue-600">
                                <svg class="mx-auto mb-1 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Thả vào đây để làm menu con
                            </div>
                        </div>
                        @endif
                    @endif
                @endforeach
            @endif

            <!-- Drop Zone for root level -->
            <div class="mt-2"
                 @dragover="if ($wire.draggingNodeId) { $wire.setDropTarget(null, 'after'); }"
                 @dragleave="if ($wire.dropTargetId === null) { $wire.clearDropTarget(); }"
                 :class="{ 'border-2 border-dashed border-blue-400 bg-blue-50': $wire.dropTargetId === null && $wire.draggingNodeId }">
                @if($this->dropTargetId === null && $this->draggingNodeId)
                    <div class="p-4 text-center text-sm text-blue-600">
                        Thả vào đây để chuyển thành menu gốc
                    </div>
                @endif
            </div>
        </div>

        <!-- Global Saving Indicator -->

        <!-- Global Saving Indicator -->
        @if($isSaving)
            <div class="border-t border-gray-200 bg-gray-50 px-4 py-2">
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="mr-2 h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Đang lưu thay đổi...
                </div>
            </div>
        @endif
    </div>
</div>
