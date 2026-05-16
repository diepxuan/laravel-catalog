<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-16 00:28:07
 */

namespace Diepxuan\Catalog\Http\Livewire\System;

use Diepxuan\Catalog\Config\SimbaRouteRegistry;
use Diepxuan\Catalog\Services\SimbaDocsMenuItem;
use Diepxuan\Catalog\Services\SimbaDocsMenuRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
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

    protected SimbaDocsMenuRepository $menus;

    public function boot(SimbaDocsMenuRepository $menus): void
    {
        $this->menus = $menus;
    }

    /**
     * Build the SimbaERP menu tree.
     *
     * @return Collection<int, object>
     */
    public function getTreeProperty(): Collection
    {
        $allMenus = $this->menus->activeMenus();
        $routeMap = $this->routeMapByMenuId();

        if ($this->search) {
            $search   = strtolower($this->search);
            $allMenus = $allMenus->filter(
                static fn ($sm) => str_contains(strtolower($sm->menuid), $search)
                    || str_contains(strtolower($sm->getDisplayName()), $search)
                    || str_contains(strtolower($sm->dllName ?? ''), $search)
                    || str_contains(strtolower($routeMap[$sm->menuid]['route'] ?? ''), $search),
            );
        }

        $menuIds = $allMenus->pluck('menuid')->flip();

        $childrenMap = $allMenus
            ->groupBy(static fn ($sm) => $sm->getParentMenuId() ?? '')
            ->map(static fn ($items) => $items->sortBy('stt')->values())
        ;

        $mappedSet = array_flip($this->mappedSimbaIds);

        return $this->buildRecursive($childrenMap, '', 0, $menuIds, $mappedSet, $routeMap);
    }

    /**
     * Total active menu count for stats.
     */
    public function getTotalProperty(): int
    {
        return $this->menus->countActiveMenus();
    }

    public function getPortalRouteCountProperty(): int
    {
        return \count($this->routeMapByMenuId());
    }

    public function getUnmappedContainerCountProperty(): int
    {
        return max(0, $this->total - $this->portalRouteCount);
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
    protected function buildRecursive(Collection $childrenMap, ?string $parentId, int $depth = 0, ?Collection $menuIds = null, array $mappedSet = [], array $routeMap = []): Collection
    {
        $result = collect();

        foreach ($childrenMap->get($parentId ?? '', collect()) as $sm) {
            $portalTarget = $routeMap[$sm->menuid] ?? null;
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
                'portalUrl'     => $portalTarget['url'] ?? null,
                'portalRoute'   => $portalTarget['route'] ?? null,
                'portalType'    => $portalTarget['type'] ?? null,
                'portalLabel'   => $portalTarget['label'] ?? null,
            ]);
            $result = $result->merge($this->buildRecursive($childrenMap, $sm->menuid, $depth + 1, $menuIds, $mappedSet, $routeMap));
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
                                'type'          => SimbaDocsMenuItem::TYPE_GROUP,
                                'isRoot'        => false,
                                'isGroup'       => true,
                                'isVoucher'     => false,
                                'isMasterData'  => false,
                                'isReport'      => false,
                                'isSetup'       => false,
                                'hasChildren'   => true,
                                'isAlreadyUsed' => isset($mappedSet[$f5Parent]),
                                'portalUrl'     => null,
                                'portalRoute'   => null,
                                'portalType'    => null,
                                'portalLabel'   => null,
                            ]);
                            foreach ($orphans as $sm) {
                                $portalTarget = $routeMap[$sm->menuid] ?? null;
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
                                    'portalUrl'     => $portalTarget['url'] ?? null,
                                    'portalRoute'   => $portalTarget['route'] ?? null,
                                    'portalType'    => $portalTarget['type'] ?? null,
                                    'portalLabel'   => $portalTarget['label'] ?? null,
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

    /**
     * @return array<string, array{route: string, url: string, type: string, label: string}>
     */
    private function routeMapByMenuId(): array
    {
        $map = [];

        foreach (SimbaRouteRegistry::routes() as $routeName => $metadata) {
            if (isset($map[$metadata['menuid']])) {
                continue;
            }

            $url = $this->portalUrl($routeName, $metadata['source_type']);
            if (null === $url) {
                continue;
            }

            $map[$metadata['menuid']] = [
                'route' => $routeName,
                'url'   => $url,
                'type'  => $metadata['source_type'],
                'label' => $this->sourceTypeLabel($metadata['source_type']),
            ];
        }

        return $map;
    }

    private function portalUrl(string $routeName, string $sourceType): ?string
    {
        if (Route::has($routeName)) {
            return route($routeName);
        }

        return match ($sourceType) {
            SimbaRouteRegistry::TYPE_REPORT     => route('simba.report', ['routeName' => $routeName]),
            SimbaRouteRegistry::TYPE_DICTIONARY => route('simba.dictionary', ['routeName' => $routeName]),
            SimbaRouteRegistry::TYPE_CUSTOM     => route('simba.process', ['routeName' => $routeName]),
            default                             => null,
        };
    }

    private function sourceTypeLabel(string $sourceType): string
    {
        return match ($sourceType) {
            SimbaRouteRegistry::TYPE_REPORT     => 'Report',
            SimbaRouteRegistry::TYPE_DICTIONARY => 'Danh muc',
            SimbaRouteRegistry::TYPE_VOUCHER    => 'Chung tu',
            SimbaRouteRegistry::TYPE_CUSTOM     => 'Portal',
            default                             => 'Portal',
        };
    }
}
