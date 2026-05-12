<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-12 13:56:45
 */

namespace Diepxuan\Catalog\Services;

use Diepxuan\Catalog\Models\NavigationMenu;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MenuTreeBuilder
{
    /**
     * Load all menus and build flattened tree structure.
     *
     * @return Collection<int, object>
     */
    public function buildFlattenedTree(): Collection
    {
        $allMenus = NavigationMenu::orderBy('order')
            ->orderBy('parent_id')
            ->get()
        ;

        $childrenMap = $allMenus
            ->groupBy('parent_id')
            ->map(static fn ($items) => $items->sortBy('order')->values())
        ;

        $result = collect();

        foreach ($childrenMap->get(null, collect()) as $rootMenu) {
            $result->push((object) [
                'id'           => $rootMenu->id,
                'parent_id'    => $rootMenu->parent_id,
                'name'         => $rootMenu->name,
                'route'        => $rootMenu->route,
                'simbaid'      => $rootMenu->simbaid,
                'order'        => $rootMenu->order,
                'level'        => 0,
                'has_children' => $childrenMap->has($rootMenu->id),
                'is_expanded'  => true,
            ]);

            $this->flattenTreeRecursive($childrenMap, $rootMenu->id, 1, $result);
        }

        return $result;
    }

    /**
     * Update menu position after drag & drop.
     */
    public function updateMenuPosition(int $menuId, ?int $newParentId, int $newOrder): array
    {
        $menu = NavigationMenu::findOrFail($menuId);

        if ($newParentId && $this->isDescendant($newParentId, $menuId)) {
            throw new \InvalidArgumentException('Cannot move menu to its own descendant.');
        }

        $oldParentId = $menu->parent_id;

        DB::transaction(static function () use ($menu, $newParentId, $newOrder, $oldParentId): void {
            $menu->parent_id = $newParentId;
            $menu->order     = $newOrder;
            $menu->save();

            // Reorder siblings in both old and new parent groups
            self::reorderSiblingsStatic($oldParentId);
            if ($newParentId !== $oldParentId) {
                self::reorderSiblingsStatic($newParentId);
            }
        });

        return [
            'id'        => $menu->id,
            'parent_id' => $menu->parent_id,
            'order'     => $menu->order,
        ];
    }

    /**
     * Get root menus sorted by order.
     */
    public function getRootMenus(): Collection
    {
        return NavigationMenu::whereNull('parent_id')
            ->orderBy('order')
            ->get()
        ;
    }

    /**
     * Update order for root menus.
     */
    public function updateRootMenuOrder(int $menuId, int $newOrder): void
    {
        $menu = NavigationMenu::findOrFail($menuId);

        if (null !== $menu->parent_id) {
            throw new \InvalidArgumentException('Menu is not a root menu.');
        }

        DB::transaction(static function () use ($menu, $newOrder): void {
            $menu->order = $newOrder;
            $menu->save();
            self::reorderSiblingsStatic(null);
        });
    }

    /**
     * Reorder menu up or down among siblings.
     */
    public function reorderMenu(int $menuId, string $direction): array
    {
        $menu = NavigationMenu::findOrFail($menuId);

        $siblings = NavigationMenu::where('parent_id', $menu->parent_id)
            ->orderBy('order')
            ->get()
        ;

        $currentIndex = $siblings->search(static fn ($item) => $item->id === $menuId);

        if (false === $currentIndex) {
            throw new \RuntimeException('Menu not found in siblings list.');
        }

        if ('up' === $direction && $currentIndex > 0) {
            $targetIndex = $currentIndex - 1;
        } elseif ('down' === $direction && $currentIndex < $siblings->count() - 1) {
            $targetIndex = $currentIndex + 1;
        } else {
            throw new \InvalidArgumentException('Cannot move menu in that direction.');
        }

        $currentMenu = $siblings[$currentIndex];
        $targetMenu  = $siblings[$targetIndex];

        DB::transaction(static function () use ($currentMenu, $targetMenu, $menu): void {
            $tempOrder          = $currentMenu->order;
            $currentMenu->order = $targetMenu->order;
            $targetMenu->order  = $tempOrder;

            $currentMenu->save();
            $targetMenu->save();

            // Ensure consistent ordering
            self::reorderSiblingsStatic($menu->parent_id);
        });

        return [
            'current_id' => $currentMenu->id,
            'target_id'  => $targetMenu->id,
            'new_order'  => $currentMenu->order,
        ];
    }

    /**
     * Update menu name, route, and simbaid.
     */
    public function updateMenu(int $menuId, string $name, ?string $route, ?string $simbaid = null): array
    {
        $menu        = NavigationMenu::findOrFail($menuId);
        $menu->name  = $name;
        $menu->route = $route;
        if (null !== $simbaid) {
            $menu->simbaid = $simbaid;
        }
        $menu->save();

        return [
            'id'      => $menu->id,
            'name'    => $menu->name,
            'route'   => $menu->route,
            'simbaid' => $menu->simbaid,
        ];
    }

    /**
     * Update only the simbaid field without touching name/route.
     */
    public function updateSimbaidOnly(int $menuId, string $simbaid): void
    {
        $menu          = NavigationMenu::findOrFail($menuId);
        $menu->simbaid = $simbaid;
        $menu->save();
    }

    /**
     * Delete menu and its children recursively.
     */
    public function deleteMenu(int $menuId): void
    {
        $ids   = $this->collectDescendantIds($menuId);
        $ids[] = $menuId;

        NavigationMenu::whereIn('id', $ids)->delete();
    }

    /**
     * Get tree for parent selection dropdown.
     */
    public function getTreeForDropdown(): Collection
    {
        $allMenus = NavigationMenu::orderBy('parent_id')
            ->orderBy('order')
            ->get()
        ;

        $childrenMap = $allMenus
            ->groupBy('parent_id')
            ->map(static fn ($items) => $items->sortBy('order')->values())
        ;

        return $this->buildDropdownTree($childrenMap, null);
    }

    /**
     * Recursively flatten tree with level information.
     */
    private function flattenTreeRecursive(
        Collection $childrenMap,
        ?int $parentId,
        int $level,
        Collection &$result
    ): void {
        foreach ($childrenMap->get($parentId, collect()) as $menu) {
            $result->push((object) [
                'id'           => $menu->id,
                'parent_id'    => $menu->parent_id,
                'name'         => $menu->name,
                'route'        => $menu->route,
                'simbaid'      => $menu->simbaid,
                'order'        => $menu->order,
                'level'        => $level,
                'has_children' => $childrenMap->has($menu->id),
                'is_expanded'  => true,
            ]);

            $this->flattenTreeRecursive($childrenMap, $menu->id, $level + 1, $result);
        }
    }

    /**
     * Check if a menu is descendant of another — uses in-memory collection to avoid N+1.
     */
    private function isDescendant(int $potentialParentId, int $nodeId): bool
    {
        // Load once from the already-cached tree if possible; otherwise query
        static $cache = null;
        if (null === $cache) {
            $cache = NavigationMenu::select('id', 'parent_id')->get()->keyBy('id');
        }

        $currentId = $nodeId;
        while (true) {
            $node = $cache->get($currentId);
            if (null === $node || null === $node->parent_id) {
                return false;
            }
            if ((int) $node->parent_id === $potentialParentId) {
                return true;
            }
            $currentId = (int) $node->parent_id;
        }
    }

    /**
     * Collect all descendant IDs for batch deletion.
     *
     * @return list<int>
     */
    private function collectDescendantIds(int $parentId): array
    {
        $children = NavigationMenu::where('parent_id', $parentId)->pluck('id');
        $ids      = $children->toArray();

        foreach ($children as $childId) {
            $ids = [...$ids, ...$this->collectDescendantIds((int) $childId)];
        }

        return $ids;
    }

    /**
     * Reorder siblings to maintain consistent order (0, 1, 2, ...).
     * Static variant for use inside transactions.
     */
    private static function reorderSiblingsStatic(?int $parentId): void
    {
        $siblings = NavigationMenu::where('parent_id', $parentId)
            ->orderBy('order')
            ->get()
        ;

        $order = 0;
        foreach ($siblings as $sibling) {
            if ($sibling->order !== $order) {
                $sibling->order = $order;
                $sibling->save();
            }
            ++$order;
        }
    }

    /**
     * Build hierarchical tree for dropdown.
     */
    private function buildDropdownTree(Collection $childrenMap, ?int $parentId): Collection
    {
        return $childrenMap->get($parentId, collect())->map(
            fn ($menu) => (object) [
                'id'       => $menu->id,
                'name'     => $menu->name,
                'route'    => $menu->route,
                'children' => $this->buildDropdownTree($childrenMap, $menu->id),
            ],
        );
    }
}
