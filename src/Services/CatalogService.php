<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-03-06
 */

namespace Diepxuan\Catalog\Services;

use Diepxuan\Catalog\Config\TimerConfig;
use Diepxuan\Catalog\Models\NavigationMenu;
use Diepxuan\Catalog\Models\SysCompany;
use Diepxuan\Catalog\Models\SysLanguage;
use Diepxuan\Catalog\Models\SysUserInfo;
use Diepxuan\Catalog\Models\User;
use Illuminate\Support\Carbon;
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

    public function menuTree(?int $parentId = null): \Illuminate\Support\Collection
    {
        return $this->menus()
            ->where('parent_id', $parentId)
            ->sortBy('order')
            ->values()
            ->map(function ($menu) {
                return (object) [
                    'id'        => $menu->id,
                    'name'      => $menu->name,
                    'route'     => $menu->route,
                    'order'     => $menu->order,
                    'parent_id' => $menu->parent_id,
                    'children'  => $this->menuTree($menu->id),
                ];
            })
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

    public function year(?int $year = null): int
    {
        if (!is_numeric($year) || $year < 2_006 || $year > 2_100) {
            $year = session('year', now()->year) ?? now()->year;
        }

        session(['year' => $year]);

        return $year;
    }

    /**
     * Get current timer settings from session.
     *
     * @return array{id: string, from: string, to: string}
     */
    public function getTimer(): array
    {
        $timeId = session('timeId', $this->getDefaultTimeId());
        $from = session('timeStart');
        $to = session('timeEnd');

        // Nếu chưa có session, tính toán mặc định
        if (!$from || !$to) {
            $result = $this->calculateTimeRange($timeId);
            $from = $result['from'];
            $to = $result['to'];
        }

        return [
            'id' => $timeId,
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
        ];
    }

    /**
     * Set timer settings and update session.
     *
     * @param array{id?: string, from?: string, to?: string} $time Timer settings
     * @return array{id: string, from: string, to: string}
     */
    public function setTimer(array $time = []): array
    {
        $timeId = $time['id'] ?? session('timeId', $this->getDefaultTimeId());
        $from = $time['from'] ?? null;
        $to = $time['to'] ?? null;

        // Tính toán date range dựa trên timeId (trừ khi là custom)
        if (TimerConfig::isCustom($timeId)) {
            // Custom mode: sử dụng from/to được cung cấp
            $from = $from ? Carbon::parse($from) : now()->startOfMonth();
            $to = $to ? Carbon::parse($to) : now()->endOfMonth();
        } else {
            // Predefined mode: tính toán từ timeId, ignore from/to
            $result = $this->calculateTimeRange($timeId);
            $from = $result['from'];
            $to = $result['to'];
        }

        // Lưu session
        session([
            'timeId' => $timeId,
            'timeStart' => $from,
            'timeEnd' => $to,
        ]);

        return [
            'id' => $timeId,
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
        ];
    }

    /**
     * Calculate date range for a given timeId.
     *
     * @param string $timeId Time period identifier
     * @return array{from: \Illuminate\Support\Carbon, to: \Illuminate\Support\Carbon}
     */
    protected function calculateTimeRange(string $timeId): array
    {
        $year = $this->year();
        $now = now();

        // Month (t01-t12)
        if (TimerConfig::isMonth($timeId)) {
            $month = TimerConfig::getMonthFromTimeId($timeId);
            $from = $now->setYear($year)->setMonth($month)->startOfMonth();
            $to = (clone $from)->endOfMonth();
        }
        // Quarter (q1-q4)
        elseif (TimerConfig::isQuarter($timeId)) {
            $quarter = TimerConfig::getQuarterFromTimeId($timeId);
            $from = $now->setYear($year)->setMonth(($quarter - 1) * 3 + 1)->startOfQuarter();
            $to = (clone $from)->addMonths(2)->endOfQuarter();
        }
        // Half year (h1, h2)
        elseif (TimerConfig::isHalfYear($timeId)) {
            if ($timeId === 'h1') {
                $from = $now->setYear($year)->setMonth(1)->startOfMonth();
                $to = $now->setYear($year)->setMonth(6)->endOfMonth();
            } else { // h2
                $from = $now->setYear($year)->setMonth(7)->startOfMonth();
                $to = $now->setYear($year)->setMonth(12)->endOfMonth();
            }
        }
        // Year (y)
        elseif (TimerConfig::isYear($timeId)) {
            $from = $now->setYear($year)->startOfYear();
            $to = $now->setYear($year)->endOfYear();
        }
        // Custom (c) - default to current month
        elseif (TimerConfig::isCustom($timeId)) {
            $from = $now->setYear($year)->startOfMonth();
            $to = (clone $from)->endOfMonth();
        }
        // Invalid timeId - fallback to current month
        else {
            $from = $now->setYear($year)->startOfMonth();
            $to = (clone $from)->endOfMonth();
            $timeId = 't' . str_pad("{$now->month}", 2, '0', STR_PAD_LEFT);
        }

        return [
            'from' => $from,
            'to' => $to,
        ];
    }

    /**
     * Get default timeId (current month).
     *
     * @return string
     */
    protected function getDefaultTimeId(): string
    {
        return 't' . str_pad((string) now()->month, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Get timer from date.
     *
     * @return string
     * @deprecated Use getTimer()['from'] instead
     */
    public function timerFrom(): string
    {
        return $this->getTimer()['from'];
    }

    /**
     * Get timer to date.
     *
     * @return string
     * @deprecated Use getTimer()['to'] instead
     */
    public function timerTo(): string
    {
        return $this->getTimer()['to'];
    }

    /**
     * Legacy timer method for backward compatibility.
     *
     * @param null|array|string $time Timer settings
     * @return array{id: string, from: string, to: string}
     * @deprecated Use getTimer() or setTimer() instead
     */
    public function timer(null|array|string $time = null): array
    {
        if ($time === null) {
            return $this->getTimer();
        }

        return $this->setTimer(\is_array($time) ? $time : ['id' => $time]);
    }

    public function ma_Nt()
    {
        return $this->maNt ?? $this->maNt = ($this->company()->siSetup->ma_nt0 ?? 'VND');
    }
}
