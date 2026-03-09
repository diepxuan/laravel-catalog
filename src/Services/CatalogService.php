<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-03-09 19:25:00
 */

namespace Diepxuan\Catalog\Services;

use Diepxuan\Catalog\Config\TimerConfig;
use Diepxuan\Catalog\Models\NavigationMenu;
use Diepxuan\Catalog\Models\SysCompany;
use Diepxuan\Catalog\Models\SysLanguage;
use Diepxuan\Catalog\Models\SysUserInfo;
use Diepxuan\Catalog\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class CatalogService
{
    protected SysLanguage $sysLanguage;

    protected User $user;

    protected SysUserInfo $simbaUser;

    protected ?SysCompany $company = null;

    protected string $maNt;

    protected string $id;

    protected $menus;

    public function __construct()
    {
        $this->id = uniqid('report_');
        // \Log::info("ReportService instance đã được khởi tạo với ID: {$this->id}");
        // \Debugbar::info('ReportService instance đã được khởi tạo với ID: ' . $this->id);
    }

    public function user()
    {
        return $this->user ?? $this->user = Auth::user();
    }

    public function simbaUser()
    {
        return $this->simbaUser ?? $this->simbaUser = $this->user()->getSimbaUser();
    }

    public function language()
    {
        return $this->sysLanguage ?? $this->sysLanguage = SysLanguage::current()->first();
    }

    public function company()
    {
        if ($this->company) {
            return $this->company;
        }

        $ma_cty = session('selected_company');
        if ($ma_cty) {
            $this->company = $this->companies()->firstWhere('ma_cty', $ma_cty);
        } else {
            $this->company = $this->companies()->first();
        }

        session(['selected_company' => $this->company->ma_cty]);

        return $this->company;
    }

    public function companies()
    {
        return $this->simbaUser()->companies;
    }

    public function menus($forceReload = false)
    {
        if ($forceReload) {
            $this->menus = NavigationMenu::withDefaultMenus();
        }

        $this->menus ??= NavigationMenu::withDefaultMenus();

        return $this->menus;
    }

    public function menuTree(?int $parentId = null): Collection
    {
        return $this->menus()
            ->where('parent_id', $parentId)
            ->sortBy('order')
            ->values()
            ->map(fn ($menu) => (object) [
                'id'        => $menu->id,
                'name'      => $menu->name,
                'route'     => $menu->route,
                'order'     => $menu->order,
                'parent_id' => $menu->parent_id,
                'children'  => $this->menuTree($menu->id),
            ])
        ;
    }

    public function reorderChildren($parentId = null): void
    {
        $this->menus()
            ->where('parent_id', $parentId)
            ->orderBy('order')
            ->values()
            ->map(function ($menu, $index) {
                $menu->order = $index;
                $menu->save();

                $menu->children = $this->reorderChildren($menu->id);

                return $menu;
            })
        ;
    }

    public static function year(?int $year = null): int
    {
        if (!is_numeric($year) || $year < 2_006 || $year > 2_100) {
            $year = session('year', now()->year) ?? now()->year;
        }

        session(['year' => $year]);

        return $year;
    }

    /**
     * Legacy timer method for backward compatibility.
     *
     * @param null|array|string $time Timer settings
     *
     * @return array{id: string, from: string, to: string}
     */
    public static function timer(array|string|null $time = null): array
    {
        $time = match (true) {
            \is_array($time) => $time,
            null === $time   => [],
            default          => ['id' => $time],
        };

        return TimerConfig::timer($time ?: null);
    }

    /**
     * Get timer from date.
     */
    public static function timerFrom(): string
    {
        return TimerConfig::timer()['from'];
    }

    /**
     * Get timer to date.
     */
    public static function timerTo(): string
    {
        return TimerConfig::timer()['to'];
    }

    public function ma_Nt()
    {
        return $this->maNt ?? $this->maNt = ($this->company()->siSetup->ma_nt0 ?? 'VND');
    }
}
