@php
    /* Build collapse/active data for Alpine:
     * - _ancestorMap: each node -> closest ancestor with children (the one that controls its visibility)
     * - collapsedIds: nodes with depth >= 1 that have children → default collapsed
     * - activeMenuId: current menu by portal URL first, route name fallback
     * - activeAncestorIds: ancestors to auto-open temporarily so active menu is visible
     */
    $tree = $this->tree;
    $ancestorMap = [];
    $collapsedIds = [];
    $activeMenuId = null;
    $normalizeMenuPath = static function (?string $path): string {
        $path = '/' . ltrim((string) $path, '/');

        return $path === '/' ? '/' : rtrim($path, '/');
    };
    $currentPath = $normalizeMenuPath(request()->path());
    $stack = []; // stack of [depth, menuid] for ancestors with children

    foreach ($tree as $item) {
        /* Pop ancestors deeper than current */
        while (!empty($stack) && end($stack)[0] >= $item->depth) {
            array_pop($stack);
        }

        /* Closest ancestor with children = last item in stack */
        $menuId = (string) $item->menuid;

        if (!empty($stack) && $item->parentId) {
            $ancestorMap[$menuId] = (string) end($stack)[1];
        }

        /* Active node: canonical /simba URL first, route-name fallback second */
        $itemPath = $item->portalUrl ? $normalizeMenuPath(parse_url($item->portalUrl, PHP_URL_PATH)) : null;
        if ($itemPath === $currentPath || (!$activeMenuId && $item->portalRoute && request()->routeIs($item->portalRoute))) {
            $activeMenuId = $menuId;
        }

        /* Push if this node has children */
        if ($item->hasChildren) {
            $stack[] = [$item->depth, $menuId];
            /* Default: collapse depth >= 1 with children */
            if ($item->depth >= 1) {
                $collapsedIds[] = $menuId;
            }
        }
    }

    $activeAncestorIds = [];
    $cursor = $activeMenuId;
    while ($cursor && isset($ancestorMap[$cursor])) {
        $cursor = $ancestorMap[$cursor];
        $activeAncestorIds[] = $cursor;
    }
@endphp

<div class="w-[360px] flex-shrink-0 rounded-lg border border-gray-200 bg-white shadow-sm"
     x-data="{
         simbaSelectMode: false,
         simbaSelectNodeId: null,
         searching: {{ Js::from(filled($search)) }},
         activeMenuId: {{ Js::from($activeMenuId) }},
         activeAncestorIds: {{ Js::from($activeAncestorIds) }},
         defaultCollapsedIds: {{ Js::from($collapsedIds) }},
         /* ancestor lookup: childId -> closest ancestor with children */
         _ancestorMap: {{ Js::from($ancestorMap) }},
         storageKey: 'portal:simba-erp-menus:collapsed:v1',
         /* collapsed Set */
         collapsed: new Set(),
         init() {
             this.loadCollapsedState();
             this.openActivePath();
             this.bindSelectModeEvents();
             this.scrollActiveNodeIntoView();
         },
         loadCollapsedState() {
             try {
                 const saved = localStorage.getItem(this.storageKey);
                 const ids = saved ? JSON.parse(saved) : this.defaultCollapsedIds;
                 this.collapsed = new Set(ids.map((id) => this.normalizeMenuId(id)));
             } catch (e) {
                 this.collapsed = new Set(this.defaultCollapsedIds.map((id) => this.normalizeMenuId(id)));
             }
         },
         normalizeMenuId(menuId) {
             return String(menuId);
         },
         persistCollapsedState() {
             try {
                 localStorage.setItem(this.storageKey, JSON.stringify([...this.collapsed]));
             } catch (e) {
                 // Ignore storage failures; keep the in-memory collapse state usable.
             }
         },
         openActivePath() {
             this.activeAncestorIds.map((id) => this.normalizeMenuId(id)).forEach((id) => this.collapsed.delete(id));
         },
         bindSelectModeEvents() {
             window.addEventListener('simba-select-mode-enter', (e) => {
                 this.simbaSelectMode = true;
                 this.simbaSelectNodeId = e.detail.nodeId;
             });
             window.addEventListener('simba-select-mode-exit', () => {
                 this.simbaSelectMode = false;
                 this.simbaSelectNodeId = null;
             });
         },
         scrollActiveNodeIntoView() {
             this.$nextTick(() => {
                 this.$root.querySelector('[data-active-menu=true]')?.scrollIntoView({ block: 'center' });
             });
         },
         selectMenuId(menuId) {
             if (!this.simbaSelectMode) return;
             window.dispatchEvent(new CustomEvent('simba-menu-selected', { detail: { menuId } }));
         },
         isCollapsed(menuId) {
             return this.collapsed.has(this.normalizeMenuId(menuId));
         },
         toggleCollapse(menuId) {
             const id = this.normalizeMenuId(menuId);
             if (this.collapsed.has(id)) {
                 this.collapsed.delete(id);
             } else {
                 this.collapsed.add(id);
             }
             this.persistCollapsedState();
         },
         isActiveNode(menuId) {
             return this.activeMenuId !== null && this.normalizeMenuId(this.activeMenuId) === this.normalizeMenuId(menuId);
         },
         isVisible(menuId) {
             if (this.searching) return true;
             /* Walk up ancestor chain; if any is collapsed -> hidden */
             let aid = this._ancestorMap[this.normalizeMenuId(menuId)];
             while (aid) {
                 aid = this.normalizeMenuId(aid);
                 if (this.collapsed.has(aid)) return false;
                 aid = this._ancestorMap[aid];
             }
             return true;
         }
     }">

    {{-- Panel Header --}}
    <div class="border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 px-4 py-3">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm font-semibold text-gray-800">SimbaERP Menus</h3>
                <p class="text-xs text-gray-500">{{ $this->portalRouteCount }} menu có Portal / {{ $this->unmappedContainerCount }} nhóm</p>
            </div>
            @if ($this->total > 0)
                <span class="rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-700">{{ $this->total }}</span>
            @endif
        </div>

        {{-- Search --}}
        <div class="mt-2">
            <div class="relative">
                <svg class="pointer-events-none absolute left-2 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text"
                       wire:model.live.debounce.300ms="search"
                       placeholder="Tìm menu, DLL, route..."
                       class="w-full rounded-md border-gray-300 py-1.5 pl-8 pr-3 text-xs shadow-sm focus:border-blue-500 focus:ring-blue-500" />
            </div>
        </div>
    </div>

    {{-- Tree --}}
    <div class="max-h-[calc(100vh-280px)] overflow-y-auto"
         :class="{ 'simba-select-active': simbaSelectMode }">
        @forelse ($tree as $item)
            @include('catalog::system.simba-node', ['item' => $item, 'activeMenuId' => $activeMenuId])
        @empty
            <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7M3 7l9 6 9-6" />
                </svg>
                <p class="mt-1 text-xs">
                    @if ($search) Không tìm thấy kết quả
                    @else Không có dữ liệu @endif
                </p>
            </div>
        @endforelse
    </div>

    {{-- Footer stats --}}
    <div class="border-t border-gray-200 bg-gray-50 px-4 py-2 text-xs text-gray-500">
        {{ $tree->count() }} menu / {{ $this->total }} total · {{ $this->portalRouteCount }} menu đã map Portal
    </div>
</div>
