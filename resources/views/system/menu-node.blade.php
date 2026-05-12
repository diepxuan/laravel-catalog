@foreach ($nodes as $node)
    @if ($this->isVisible($node->id))
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

        {{-- Drop Zone Before --}}
        @if (!$isDragging)
            <div class="my-1"
                @dragover="if ($wire.draggingNodeId && $wire.draggingNodeId !== {{ $nodeId }}) {
                 $wire.setDropTarget({{ $nodeId }}, 'before');
                 event.dataTransfer.dropEffect = 'move';
             }"
                @dragleave="if ($wire.dropTargetId === {{ $nodeId }} && $wire.dropPosition === 'before') {
                 $wire.clearDropTarget();
             }"
                :class="{
                    'border-t-2 border-blue-400': $wire.dropTargetId === {{ $nodeId }} &&
                        $wire.dropPosition === 'before'
                }">
            </div>
        @endif

        {{-- Node Row --}}
        <div class="{{ $isRecentlyUpdated ? 'border-green-300 bg-green-50' : 'border-gray-200' }} {{ $isDragging ? 'opacity-50' : '' }} {{ $isDropTarget && $dropPosition === 'inside' ? 'border-2 border-dashed border-blue-400 bg-blue-100' : '' }} group relative flex items-center rounded-lg border transition-all duration-200 hover:border-gray-300 hover:bg-gray-50"
            draggable="true"
            @dragover="if ($wire.draggingNodeId && $wire.draggingNodeId !== {{ $nodeId }}) {
                 $wire.setDropTarget({{ $nodeId }}, 'inside');
                 event.dataTransfer.dropEffect = 'move';
                 event.preventDefault();
             }"
            @dragleave="if ($wire.dropTargetId === {{ $nodeId }} && $wire.dropPosition === 'inside') {
                 $wire.clearDropTarget();
             }"
            @dragstart="$wire.startDrag({{ $nodeId }})" @dragend="$wire.clearDragState()"
            style="padding-left: {{ $node->level * 24 }}px">

            {{-- Expand/Collapse --}}
            @if ($hasChildren)
                <button type="button"
                    class="ml-2 flex h-6 w-6 items-center justify-center text-gray-500 hover:text-gray-700"
                    wire:click="toggleNode({{ $nodeId }})">
                    <svg class="{{ $isExpanded ? 'rotate-90' : '' }} h-4 w-4 transition-transform duration-200"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            @else
                <div class="ml-8 w-2"></div>
            @endif

            {{-- Drag Handle --}}
            <div class="ml-1 cursor-move text-gray-400 hover:text-gray-600" title="Kéo để di chuyển">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                </svg>
            </div>

            {{-- Content --}}
            <div class="flex flex-1 items-center px-3 py-2">
                {{-- Name --}}
                <div class="col-span-4 w-4/12">
                    @if ($isEditing)
                        <input type="text"
                            class="w-full rounded-md border-gray-300 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            wire:model="editingName" wire:keydown.enter="saveEdit"
                            wire:keydown.escape="cancelEdit" autofocus />
                    @else
                        <button type="button"
                            class="text-left font-medium text-gray-900 hover:text-blue-600"
                            wire:click="startEdit({{ $nodeId }})">
                            {{ $node->name }}
                        </button>
                    @endif
                </div>

                {{-- Route --}}
                <div class="col-span-3 w-3/12">
                    @if ($isEditing)
                        <input type="text"
                            class="w-full rounded-md border-gray-300 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            wire:model="editingRoute" wire:keydown.enter="saveEdit"
                            wire:keydown.escape="cancelEdit" placeholder="route.name" />
                    @else
                        <span class="text-sm text-gray-600">{{ $node->route ?? '—' }}</span>
                    @endif
                </div>

                {{-- SimbaID --}}
                <div class="col-span-2 w-24 text-center">
                    @if ($isEditing)
                        <input type="text"
                            class="w-full rounded-md border-gray-300 py-1 text-sm font-mono shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            wire:model="editingSimbaid" wire:keydown.enter="saveEdit"
                            wire:keydown.escape="cancelEdit" placeholder="02.00.00" />
                    @else
                        <button type="button"
                            class="w-full rounded px-1 text-xs font-mono transition-colors"
                            :class="simbaSelectMode && simbaSelectNodeId === {{ $nodeId }}
                                ? 'pulse-red-simba'
                                : 'text-gray-500 hover:text-blue-600'"
                            title="Click để chọn SimbaID"
                            @click="$dispatch('simba-select-mode-enter', { nodeId: {{ $nodeId }} });
                                   $wire.call('enterSimbaSelectMode', {{ $nodeId }})">
                            {{ $node->simbaid ?? '—' }}
                        </button>
                    @endif
                </div>

                {{-- ID --}}
                <div class="col-span-1 w-16 text-center">
                    <span class="rounded-full bg-gray-100 px-2 py-1 font-mono text-xs text-gray-700">{{ $nodeId }}</span>
                </div>

                {{-- Order --}}
                <div class="col-span-1 w-24 text-center">
                    <div class="flex items-center justify-center space-x-1">
                        @php
                            $canMoveUp = $this->canMoveUp($nodeId);
                            $canMoveDown = $this->canMoveDown($nodeId);
                            $isSavingOrder = $this->nodeSaving[$nodeId] ?? false;
                        @endphp
                        <button type="button"
                            class="{{ !$canMoveUp ? 'opacity-30 cursor-not-allowed' : '' }} rounded-md p-1 text-gray-500 hover:bg-gray-100 hover:text-gray-700"
                            wire:click="reorderMenu({{ $nodeId }}, 'up')" title="Di chuyển lên"
                            {{ !$canMoveUp ? 'disabled' : '' }} wire:loading.attr="disabled">
                            @if ($isSavingOrder)
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
                        <span class="min-w-[20px] text-sm font-medium text-gray-700">{{ $node->order }}</span>
                        <button type="button"
                            class="{{ !$canMoveDown ? 'opacity-30 cursor-not-allowed' : '' }} rounded-md p-1 text-gray-500 hover:bg-gray-100 hover:text-gray-700"
                            wire:click="reorderMenu({{ $nodeId }}, 'down')" title="Di chuyển xuống"
                            {{ !$canMoveDown ? 'disabled' : '' }} wire:loading.attr="disabled">
                            @if ($isSavingOrder)
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

                {{-- Actions --}}
                <div class="ml-4 flex items-center space-x-1 {{ $isEditing ? 'opacity-100' : 'opacity-0 transition-opacity group-hover:opacity-100' }}">
                    @if ($isEditing)
                        {{-- Save --}}
                        <button type="button"
                            class="rounded-md bg-green-100 p-1 text-green-700 hover:bg-green-200"
                            wire:click="saveEdit" wire:loading.attr="disabled" wire:target="saveEdit"
                            title="Lưu">
                            @if ($isSavingNode)
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
                        {{-- Cancel --}}
                        <button type="button"
                            class="rounded-md bg-gray-100 p-1 text-gray-700 hover:bg-gray-200"
                            wire:click="cancelEdit" title="Hủy">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    @else
                        {{-- Edit --}}
                        <button type="button"
                            class="rounded-md p-1 text-gray-500 hover:bg-gray-100 hover:text-gray-700"
                            wire:click="startEdit({{ $nodeId }})" title="Chỉnh sửa">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        {{-- Delete --}}
                        <button type="button"
                            class="rounded-md p-1 text-red-500 hover:bg-red-100 hover:text-red-700"
                            wire:click="deleteNode({{ $nodeId }})"
                            wire:confirm="Bạn có chắc chắn muốn xóa menu này và tất cả menu con?"
                            title="Xóa"
                            wire:loading.attr="disabled">
                            @if ($isSavingNode)
                                <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            @else
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            @endif
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Drop Zone After --}}
        @if (!$isDragging)
            <div class="my-1"
                @dragover="if ($wire.draggingNodeId && $wire.draggingNodeId !== {{ $nodeId }}) {
                 $wire.setDropTarget({{ $nodeId }}, 'after');
                 event.dataTransfer.dropEffect = 'move';
             }"
                @dragleave="if ($wire.dropTargetId === {{ $nodeId }} && $wire.dropPosition === 'after') {
                 $wire.clearDropTarget();
             }"
                :class="{
                    'border-t-2 border-blue-400': $wire.dropTargetId === {{ $nodeId }} &&
                        $wire.dropPosition === 'after'
                }">
            </div>
        @endif

        {{-- Drop Zone Inside (children area) --}}
        @if ($isExpanded && $this->draggingNodeId !== null)
            <div class="ml-[{{ $node->level * 24 + 8 }}px] my-1 rounded-lg border-2 border-dashed border-blue-300 bg-blue-50 p-2 transition-all duration-200"
                @dragover="if ($wire.draggingNodeId && $wire.draggingNodeId !== {{ $nodeId }}) {
                 $wire.setDropTarget({{ $nodeId }}, 'inside');
                 event.dataTransfer.dropEffect = 'move';
             }"
                @dragleave="if ($wire.dropTargetId === {{ $nodeId }} && $wire.dropPosition === 'inside') {
                 $wire.clearDropTarget();
             }">
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
