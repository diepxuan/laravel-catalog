<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-16 00:28:11
 */

namespace Diepxuan\Catalog\Services;

use Illuminate\Support\Collection;

/**
 * Reads SimbaERP menu metadata from checked-in simba-docs markdown exports.
 *
 * This keeps Portal menu/reference flows off the live SQL Server while still
 * using SimbaERP's exported sysMenu/zsysmenu data as source of truth.
 */
final class SimbaDocsMenuRepository
{
    /**
     * @var null|Collection<int, SimbaDocsMenuItem>
     */
    private ?Collection $cache = null;

    private SimbaDocsDataRepository $data;

    public function __construct(?string $projectRoot = null, ?SimbaDocsDataRepository $data = null)
    {
        $this->data = $data ?? new SimbaDocsDataRepository($projectRoot);
    }

    /**
     * @return Collection<int, SimbaDocsMenuItem>
     */
    public function activeMenus(): Collection
    {
        return $this->allMenus()
            ->filter(static fn (SimbaDocsMenuItem $menu) => '1' === trim((string) $menu->active))
            ->sortBy(static fn (SimbaDocsMenuItem $menu) => $menu->stt ?: $menu->menuid)
            ->values()
        ;
    }

    public function countActiveMenus(): int
    {
        return $this->activeMenus()->count();
    }

    /**
     * @return Collection<int, SimbaDocsMenuItem>
     */
    public function allMenus(): Collection
    {
        if (null !== $this->cache) {
            return $this->cache;
        }

        $rows = collect();
        foreach (['sysMenu.md', 'zsysmenu.md'] as $fileName) {
            $rows = $rows->merge($this->data->table($fileName));
        }

        $this->cache = $rows
            ->filter(static fn (array $row) => !empty($row['menuid']))
            ->unique(static fn (array $row) => $row['menuid'])
            ->map(static fn (array $row) => new SimbaDocsMenuItem($row))
            ->values()
        ;

        return $this->cache;
    }
}
