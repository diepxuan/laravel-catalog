<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-12 14:30:44
 */

namespace Diepxuan\Catalog\Http\Livewire\System;

use Diepxuan\Simba\Models\SysMenu;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Displays the SimbaERP sysMenu tree for reference.
 * Decoupled from the Portal Menu management component.
 */
class SimbaMenuTree extends Component
{
    public string $search = '';

    /**
     * SimbaIDs already mapped in Portal menus (passed from parent).
     *
     * @var list<string>
     */
    public array $mappedSimbaIds = [];

    /**
     * Build the SimbaERP menu tree.
     *
     * @return Collection<int, object>
     */
    public function getTreeProperty(): Collection
    {
        try {
            $allMenus = SysMenu::where('active', 1)
                ->orderBy('stt')
                ->get()
            ;

            if ($this->search) {
                $search   = strtolower($this->search);
                $allMenus = $allMenus->filter(
                    static fn ($sm) => str_contains(strtolower($sm->menuid), $search)
                        || str_contains(strtolower($sm->getDisplayName()), $search)
                        || str_contains(strtolower($sm->dllName ?? ''), $search),
                );
            }

            $menuIds = $allMenus->pluck('menuid')->flip();

            $childrenMap = $allMenus
                ->groupBy(static fn ($sm) => $sm->getParentMenuId() ?? '')
                ->map(static fn ($items) => $items->sortBy('stt')->values())
            ;

            $mappedSet = array_flip($this->mappedSimbaIds);

            return $this->buildRecursive($childrenMap, '', 0, $menuIds, $mappedSet);
        } catch (\Throwable) {
            return collect();
        }
    }

    /**
     * Total active menu count for stats.
     */
    public function getTotalProperty(): int
    {
        try {
            return SysMenu::where('active', 1)->count();
        } catch (\Throwable) {
            return 0;
        }
    }

    public function render(): View
    {
        return view('catalog::system.simba-menu-tree');
    }

    /**
     * Recursive tree builder.
     *
     * When a group XX.80.00 has children but doesn't exist as a menu record,
     * auto-create a synthetic "F5" group node under XX.00.00.
     *
     * @param Collection<string, Collection> $childrenMap
     * @param Collection<string, mixed>      $menuIds     flip of all menuid values
     * @param array<string, int>             $mappedSet   flip of mapped simbaid values
     */
    protected function buildRecursive(Collection $childrenMap, ?string $parentId, int $depth = 0, ?Collection $menuIds = null, array $mappedSet = []): Collection
    {
        $result = collect();

        foreach ($childrenMap->get($parentId ?? '', collect()) as $sm) {
            $result->push((object) [
                'menuid'        => $sm->menuid,
                'name'          => $sm->getDisplayName(),
                'dllName'       => $sm->dllName,
                'depth'         => $depth,
                'parentId'      => $parentId,
                'type'          => $sm->type,
                'isRoot'        => $sm->isRoot(),
                'isGroup'       => $sm->isGroup(),
                'isVoucher'     => $sm->isVoucher(),
                'isMasterData'  => $sm->isMasterData(),
                'isReport'      => $sm->isReport(),
                'isSetup'       => $sm->isSetup(),
                'hasChildren'   => $childrenMap->has($sm->menuid),
                'isAlreadyUsed' => isset($mappedSet[$sm->menuid]),
            ]);
            $result = $result->merge($this->buildRecursive($childrenMap, $sm->menuid, $depth + 1, $menuIds));
        }

        // Attach synthetic F5 group nodes under each root (only at top level)
        // Insert right after the root's existing subtree, not at the very end
        if (0 === $depth && null !== $menuIds) {
            $ordered = collect();

            // Get root IDs in order they appear in $result
            $rootIds = [];
            foreach ($result as $node) {
                if (0 === $node->depth) {
                    $rootIds[] = $node->menuid;
                }
            }

            foreach ($rootIds as $rootId) {
                $rootIndex = null;
                foreach ($result as $i => $node) {
                    if ($node->menuid === $rootId && 0 === $node->depth) {
                        $rootIndex = $i;

                        break;
                    }
                }
                if (null === $rootIndex) {
                    continue;
                }

                // Find the end of this root's subtree (next item at depth 0, or end)
                $subtreeEnd = null;
                for ($j = $rootIndex + 1; $j < $result->count(); ++$j) {
                    if (0 === $result[$j]->depth) {
                        $subtreeEnd = $j;

                        break;
                    }
                }

                // Add this root's subtree to ordered
                $sliceEnd = $subtreeEnd ?? $result->count();
                for ($i = $rootIndex; $i < $sliceEnd; ++$i) {
                    $ordered->push($result[$i]);
                }

                // Insert synthetic F5 group if needed
                $parts = explode('.', $rootId);
                if (3 === \count($parts) && '00' === $parts[1] && '00' === $parts[2]) {
                    $f5Parent = $parts[0] . '.80.00';
                    if (!$menuIds->has($f5Parent)) {
                        $orphans = $childrenMap->get($f5Parent, collect());
                        if ($orphans->isNotEmpty()) {
                            $ordered->push((object) [
                                'menuid'        => $f5Parent,
                                'name'          => 'F5',
                                'dllName'       => null,
                                'depth'         => 1,
                                'parentId'      => $rootId,
                                'type'          => SysMenu::TYPE_GROUP,
                                'isRoot'        => false,
                                'isGroup'       => true,
                                'isVoucher'     => false,
                                'isMasterData'  => false,
                                'isReport'      => false,
                                'isSetup'       => false,
                                'hasChildren'   => true,
                                'isAlreadyUsed' => isset($mappedSet[$f5Parent]),
                            ]);
                            foreach ($orphans as $sm) {
                                $ordered->push((object) [
                                    'menuid'        => $sm->menuid,
                                    'name'          => $sm->getDisplayName(),
                                    'dllName'       => $sm->dllName,
                                    'depth'         => 2,
                                    'parentId'      => $f5Parent,
                                    'type'          => $sm->type,
                                    'isRoot'        => $sm->isRoot(),
                                    'isGroup'       => $sm->isGroup(),
                                    'isVoucher'     => $sm->isVoucher(),
                                    'isMasterData'  => $sm->isMasterData(),
                                    'isReport'      => $sm->isReport(),
                                    'isSetup'       => $sm->isSetup(),
                                    'hasChildren'   => $childrenMap->has($sm->menuid),
                                    'isAlreadyUsed' => isset($mappedSet[$sm->menuid]),
                                ]);
                            }
                        }
                    }
                }
            }

            $result = $ordered;
        }

        return $result;
    }
}
