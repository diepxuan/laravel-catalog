<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-06-06 08:25:00
 */

namespace Diepxuan\Catalog\Services;

use Diepxuan\Simba\Models\SysMenu;
use Diepxuan\Simba\Models\Zsysmenu;
use Illuminate\Support\Collection;

/**
 * Reads SimbaERP menu metadata directly from SQL Server.
 */
final class SimbaMenuRepository
{
    /**
     * @var null|Collection<int, SysMenu>
     */
    private ?Collection $cache = null;

    /**
     * @return Collection<int, SysMenu>
     */
    public function activeMenus(): Collection
    {
        return $this->allMenus()
            ->filter(static fn (SysMenu $menu): bool => (bool) $menu->active)
            ->sortBy(static fn (SysMenu $menu): string => (string) ($menu->stt ?: $menu->menuid))
            ->values()
        ;
    }

    public function countActiveMenus(): int
    {
        return $this->activeMenus()->count();
    }

    /**
     * @return Collection<int, SysMenu>
     */
    public function allMenus(): Collection
    {
        if (null !== $this->cache) {
            return $this->cache;
        }

        return $this->cache = SysMenu::query()
            ->get()
            ->merge(Zsysmenu::query()->get())
            ->filter(static fn (SysMenu $menu): bool => '' !== trim((string) $menu->menuid))
            ->unique(static fn (SysMenu $menu): string => (string) $menu->menuid)
            ->values()
        ;
    }
}
