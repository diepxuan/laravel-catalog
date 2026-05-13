<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-12 19:54:54
 */

namespace Diepxuan\Catalog\Http\Livewire\System;

use Diepxuan\Catalog\Models\NavigationMenu;
use Diepxuan\Catalog\Services\MenuTreeBuilder;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class Menu extends Component
{
    public Collection $nodes;
    public ?int $editingNodeId    = null;
    public string $editingName    = '';
    public string $editingRoute   = '';
    public ?int $draggingNodeId   = null;
    public ?int $dropTargetId     = null;
    public string $dropPosition   = 'inside';
    public bool $isSaving         = false;
    public array $recentlyUpdated = [];
    public array $nodeSaving      = [];

    public array $newMenu = [
        'parent_id' => null,
        'name'      => '',
        'route'     => '',
        'simbaid'   => '',
    ];

    public string $editingSimbaid = '';

    /**
     * Node currently in SimbaID selection mode (separate from editing).
     */
    public ?int $selectingNodeId = null;

    protected MenuTreeBuilder $treeBuilder;

    public function boot(MenuTreeBuilder $treeBuilder): void
    {
        $this->treeBuilder = $treeBuilder;
    }

    public function mount(): void
    {
        $this->refreshTree();
    }

    public function refreshTree(): void
    {
        $this->nodes = $this->treeBuilder->buildFlattenedTree();
    }

    // ─── CRUD ───────────────────────────────────────────────

    public function startEdit(int $nodeId): void
    {
        $node = $this->nodes->firstWhere('id', $nodeId);
        if ($node) {
            $this->editingNodeId  = $nodeId;
            $this->editingName    = $node->name;
            $this->editingRoute   = $node->route ?? '';
            $this->editingSimbaid = $node->simbaid ?? '';
            // Entering edit mode exits selection mode
            if (null !== $this->selectingNodeId) {
                $this->selectingNodeId = null;
            }
        }
    }

    public function cancelEdit(): void
    {
        $this->editingNodeId  = null;
        $this->editingName    = '';
        $this->editingRoute   = '';
        $this->editingSimbaid = '';
    }

    /**
     * Enter SimbaID selection mode for a specific node.
     * Visual state handled by Alpine; backend tracks for validation.
     */
    public function enterSimbaSelectMode(int $nodeId): void
    {
        if (null !== $this->editingNodeId && $this->editingNodeId !== $nodeId) {
            return;
        }

        $this->selectingNodeId = $nodeId;
    }

    /**
     * Set simbaid from SimbaERP menu selection and save directly (no edit mode).
     */
    public function selectSimbaid(string $menuId): void
    {
        if (!$this->selectingNodeId) {
            return;
        }

        $nodeId                = $this->selectingNodeId;
        $this->selectingNodeId = null;

        $this->treeBuilder->updateSimbaidOnly($nodeId, $menuId);

        $node = $this->nodes->firstWhere('id', $nodeId);
        if ($node) {
            $node->simbaid = $menuId;
        }

        $this->recentlyUpdated[$nodeId] = true;
        $this->dispatch('clear-highlight', nodeId: $nodeId);
        $this->dispatch('simba-select-mode-exit');
    }

    /**
     * Get all simbaid values already used in Portal menus.
     *
     * @return array<string, string>
     */
    public function getMappedSimbaIdsProperty(): array
    {
        return NavigationMenu::whereNotNull('simbaid')
            ->where('simbaid', '!=', '')
            ->pluck('simbaid')
            ->toArray()
        ;
    }

    public function saveEdit(): void
    {
        if (!$this->editingNodeId) {
            return;
        }

        $this->nodeSaving[$this->editingNodeId] = true;

        try {
            $updated = $this->treeBuilder->updateMenu(
                $this->editingNodeId,
                $this->editingName,
                $this->editingRoute,
                $this->editingSimbaid
            );

            $node = $this->nodes->firstWhere('id', $this->editingNodeId);
            if ($node) {
                $node->name    = $updated['name'];
                $node->route   = $updated['route'];
                $node->simbaid = $updated['simbaid'] ?? '';
            }

            $this->recentlyUpdated[$this->editingNodeId] = true;
            $this->dispatch('clear-highlight', nodeId: $this->editingNodeId);

            $this->cancelEdit();
        } finally {
            unset($this->nodeSaving[$this->editingNodeId]);
        }
    }

    public function startDrag(int $nodeId): void
    {
        $this->draggingNodeId = $nodeId;
    }

    public function setDropTarget(?int $nodeId, string $position = 'inside'): void
    {
        $this->dropTargetId = $nodeId;
        $this->dropPosition = $position;
    }

    public function clearDropTarget(): void
    {
        $this->dropTargetId = null;
        $this->dropPosition = 'inside';
    }

    public function clearDragState(): void
    {
        $this->draggingNodeId = null;
        $this->dropTargetId   = null;
        $this->dropPosition   = 'inside';
    }

    public function handleDrop(): void
    {
        if (!$this->draggingNodeId) {
            return;
        }

        $this->isSaving = true;

        try {
            $newOrder    = 0;
            $newParentId = $this->dropTargetId;

            if ('before' === $this->dropPosition || 'after' === $this->dropPosition) {
                $targetNode = $this->nodes->firstWhere('id', $this->dropTargetId);
                if ($targetNode) {
                    $newParentId = $targetNode->parent_id;
                    $newOrder    = $targetNode->order;

                    if ('after' === $this->dropPosition) {
                        ++$newOrder;
                    }
                }
            } elseif ('inside' === $this->dropPosition) {
                $newParentId   = $this->dropTargetId;
                $maxChildOrder = $this->nodes->where('parent_id', $newParentId)->max('order') ?? -1;
                $newOrder      = $maxChildOrder + 1;
            }

            if (null === $this->dropTargetId) {
                $newParentId = null;
                $maxOrder    = $this->nodes->where('parent_id', null)->max('order') ?? -1;
                $newOrder    = $maxOrder + 1;
            }

            if ($newParentId && $this->isDescendant($newParentId, $this->draggingNodeId)) {
                $this->dispatch('show-error', message: 'Không thể di chuyển menu vào chính nó hoặc menu con của nó.');

                return;
            }

            $this->treeBuilder->updateMenuPosition(
                $this->draggingNodeId,
                $newParentId,
                $newOrder
            );

            $this->refreshTree();
        } finally {
            $this->isSaving = false;
            $this->clearDragState();
        }
    }

    public function reorderMenu(int $menuId, string $direction): void
    {
        $menu = $this->nodes->firstWhere('id', $menuId);
        if (!$menu) {
            return;
        }

        $this->nodeSaving[$menuId] = true;

        try {
            $this->treeBuilder->reorderMenu($menuId, $direction);
            $this->refreshTree();
        } finally {
            unset($this->nodeSaving[$menuId]);
        }
    }

    public function canMoveUp(int $menuId): bool
    {
        $menu = $this->nodes->firstWhere('id', $menuId);
        if (!$menu) {
            return false;
        }

        if (null === $menu->parent_id) {
            $rootMenus    = $this->nodes->where('parent_id', null)->sortBy('order')->values();
            $currentIndex = $rootMenus->search(static fn ($item) => $item->id === $menuId);

            return $currentIndex > 0;
        }

        $siblings     = $this->nodes->where('parent_id', $menu->parent_id)->sortBy('order')->values();
        $currentIndex = $siblings->search(static fn ($item) => $item->id === $menuId);

        return $currentIndex > 0;
    }

    public function canMoveDown(int $menuId): bool
    {
        $menu = $this->nodes->firstWhere('id', $menuId);
        if (!$menu) {
            return false;
        }

        if (null === $menu->parent_id) {
            $rootMenus    = $this->nodes->where('parent_id', null)->sortBy('order')->values();
            $currentIndex = $rootMenus->search(static fn ($item) => $item->id === $menuId);

            return false !== $currentIndex && $currentIndex < $rootMenus->count() - 1;
        }

        $siblings     = $this->nodes->where('parent_id', $menu->parent_id)->sortBy('order')->values();
        $currentIndex = $siblings->search(static fn ($item) => $item->id === $menuId);

        return false !== $currentIndex && $currentIndex < $siblings->count() - 1;
    }

    public function deleteNode(int $nodeId): void
    {
        $this->nodeSaving[$nodeId] = true;

        try {
            $this->treeBuilder->deleteMenu($nodeId);
            $this->nodes = $this->nodes->reject(static fn ($node) => $node->id === $nodeId);
        } finally {
            unset($this->nodeSaving[$nodeId]);
        }
    }

    public function addMenu(): void
    {
        $this->validate([
            'newMenu.name'      => 'required|string|max:255',
            'newMenu.route'     => 'nullable|string|max:255',
            'newMenu.parent_id' => 'nullable|exists:menus,id',
            'newMenu.simbaid'   => 'nullable|string|max:20',
        ]);

        NavigationMenu::create($this->newMenu);

        $this->newMenu = [
            'parent_id' => null,
            'name'      => '',
            'route'     => '',
            'simbaid'   => '',
        ];

        $this->refreshTree();
    }

    /**
     * Check if a node has children.
     */
    public function hasChildren(int $nodeId): bool
    {
        $node = $this->nodes->firstWhere('id', $nodeId);

        return (bool) $node?->has_children;
    }

    /**
     * Build ancestor map: each node ID → closest ancestor with children.
     * Used for client-side visibility calculation.
     *
     * @return array<int, int>
     */
    public function getNodeAncestorMapProperty(): array
    {
        $map   = [];
        $stack = [];

        foreach ($this->nodes as $node) {
            while (\count($stack) && end($stack)[0] >= $node->level) {
                array_pop($stack);
            }
            if (\count($stack) && null !== $node->parent_id) {
                $map[$node->id] = end($stack)[1];
            }
            if ($node->has_children) {
                $stack[] = [$node->level, $node->id];
            }
        }

        return $map;
    }

    /**
     * IDs of nodes collapsed by default (all nodes with children).
     *
     * @return list<int>
     */
    public function getInitiallyCollapsedIdsProperty(): array
    {
        return $this->nodes
            ->filter(static fn ($n) => $n->has_children)
            ->pluck('id')
            ->toArray()
        ;
    }

    public function getParentOptionsProperty(): Collection
    {
        return $this->treeBuilder->getTreeForDropdown();
    }

    public function render(): View
    {
        return view('catalog::system.menu')->layout('catalog::layouts.app');
    }

    private function isDescendant(int $potentialParentId, int $nodeId): bool
    {
        $node = $this->nodes->firstWhere('id', $nodeId);
        while ($node && $node->parent_id) {
            if ($node->parent_id === $potentialParentId) {
                return true;
            }
            $node = $this->nodes->firstWhere('id', $node->parent_id);
        }

        return false;
    }
}
