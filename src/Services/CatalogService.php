<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-07-13 22:33:36
 */

namespace Diepxuan\Catalog\Services;

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

    public function __construct()
    {
        $this->id = uniqid('report_');
        \Log::info('ReportService instance đã được khởi tạo với ID: ' . $this->id);
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

    public function year(?int $year = null): int
    {
        if (!is_numeric($year) || $year < 2_006 || $year > 2_100) {
            $year = session('year', now()->year) ?? now()->year;
        }

        session(['year' => $year]);

        return $year;
    }

    public function timer(null|array|string $time = null)
    {
        $year = $this->year();

        // Xử lý input ban đầu
        $timeId = \is_array($time) ? ($time['id'] ?? session('timeId', 'y')) : ($time ?? session('timeId', 'y'));
        $from   = \is_array($time) && !empty($time['from']) ? Carbon::parse($time['from']) : session('timeStart');
        $to     = \is_array($time) && !empty($time['to']) ? Carbon::parse($time['to']) : session('timeEnd');

        // Default fallback nếu null
        $from ??= now()->setYear($year)->startOfYear();
        $to   ??= now()->setYear($year)->endOfYear();

        // Xử lý timeId logic
        $monthList   = collect(range(1, 12))->map(static fn ($m) => 't' . str_pad("{$m}", 2, '0', STR_PAD_LEFT))->toArray();
        $quarterList = collect(range(1, 4))->map(static fn ($q) => "q{$q}")->toArray();
        if (\in_array($timeId, $monthList, true)) {
            // $timeId nằm trong 't01' đến 't12'
            $month = (int) substr($timeId, 1);
            $from  = now()->setYear($year)->setMonth($month)->startOfMonth();
            $to    = (clone $from)->endOfMonth();
            // \Debugbar::info($timeId, $month, $from);
        } elseif (\in_array($timeId, $quarterList, true)) {
            // $timeId nằm trong 'q1' đến 'q4'
            $quarter = (int) substr($timeId, 1);
            $from    = now()->setYear($year)->setMonth(($quarter - 1) * 3 + 1)->startOfQuarter();
            $to      = (clone $from)->addMonths(2)->endOfQuarter();
        } elseif ('h1' === $timeId) {
            $from = now()->setYear($year)->setMonth(1)->startOfMonth(); // Tháng 1
            $to   = now()->setYear($year)->setMonth(6)->endOfMonth();   // Tháng 6
        } elseif ('h2' === $timeId) {
            $from = now()->setYear($year)->setMonth(7)->startOfMonth(); // Tháng 7
            $to   = now()->setYear($year)->setMonth(12)->endOfMonth();  // Tháng 12
        } elseif ('c' === $timeId) {
            // $timeId là 'c', không cần thay đổi $from và $to
            $from ??= now()->startOfMonth();
            $to   ??= now()->endOfMonth();
        } else {
            $timeId = 'y';
            $from   = now()->setYear($year)->startOfYear();
            $to     = (clone $from)->endOfYear();
        }
        session([
            'timeId'    => $timeId,
            'timeStart' => $from,
            'timeEnd'   => $to,
        ]);

        // \Debugbar::info($timeId, $from->toDateString(), $to->toDateString());

        return [
            'id'   => $timeId,
            'from' => $from->toDateString(),
            'to'   => $to->toDateString(),
        ];
    }

    public function timerFrom()
    {
        return $this->timer()['from'];
    }

    public function timerTo()
    {
        return $this->timer()['to'];
    }

    public function ma_Nt()
    {
        return $this->maNt ?? $this->maNt = ($this->company()->siSetup->ma_nt0 ?? 'VND');
    }
}
