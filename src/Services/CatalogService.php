<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-05-27 10:11:08
 */

namespace Diepxuan\Catalog\Services;

use Carbon\Carbon;
use Diepxuan\Catalog\Models\SysLanguage;
use Illuminate\Support\Facades\Auth;

class CatalogService
{
    public function user()
    {
        return Auth::user();
    }

    public function simbaUser()
    {
        return $this->user()->getSimbaUser();
    }

    public function language()
    {
        return SysLanguage::current()->first();
    }

    public function company()
    {
        $ma_cty = session('selected_company');
        if ($ma_cty) {
            $company = $this->companies()->firstWhere('ma_cty', $ma_cty);
        } else {
            $company = $this->companies()->first();
        }

        session(['selected_company' => $company->ma_cty]);

        return $company;
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

    public function timer($time = null)
    {
        $year = $this->year();
        if (\is_array($time)) {
            $timeId = $time['id'] ?? session('timeId', 'y') ?? 'y';
            $from   = $time['from'] ? Carbon::parse($time['from']) : session('timeStart', now()->setYear($year)->startOfYear()) ?? now()->setYear($year)->startOfYear();
            $to     = $time['to'] ? Carbon::parse($time['to']) : session('timeEnd', now()->setYear($year)->endOfYear()) ?? now()->setYear($year)->endOfYear();
        } else {
            $timeId = $time ?? session('timeId', 'y') ?? 'y';
            $from ??= session('timeStart', now()->setYear($year)->startOfYear()) ?? now()->setYear($year)->startOfYear();
            $to   ??= session('timeEnd', now()->setYear($year)->endOfYear()) ?? now()->setYear($year)->endOfYear();
        }
        // Carbon::parse('2025-03-31');

        if (\in_array($timeId, array_map(static fn ($n) => 't' . str_pad("{$n}", 2, '0', STR_PAD_LEFT), range(1, 12)), true)) {
            // $timeId nằm trong 't01' đến 't12'
            $month = (int) substr($timeId, 1);
            $from  = now()->setYear($year)->setMonth($month)->startOfMonth();
            $to    = (clone $from)->endOfMonth();
        } elseif (\in_array($timeId, array_map(static fn ($n) => 'q' . $n, range(1, 4)), true)) {
            // $timeId nằm trong 'q1' đến 'q4'
            $quarter = (int) substr($timeId, 1);
            $from    = now()->setYear($year)->setMonth(($quarter - 1) * 3 + 1)->startOfQuarter();
            $to      = (clone $from)->addMonths(2)->endOfQuarter();
        } elseif ('h1' === $timeId) {
            $from = now()->setYear($year)->setMonth(1)->startOfMonth(); // Tháng 1
            $to   = (clone $from)->setMonth(6)->endOfMonth();   // Tháng 6
        } elseif ('h2' === $timeId) {
            $from = now()->setYear($year)->setMonth(7)->startOfMonth(); // Tháng 7
            $to   = (clone $from)->setMonth(12)->endOfMonth();  // Tháng 12
        } elseif ('c' === $timeId) {
            // $timeId là 'c', không cần thay đổi $from và $to
            if (null === $from) {
                $from = session('timeStart', now()->startOfMonth()) ?? now()->startOfMonth();
            }
            if (null === $to) {
                $to = session('timeEnd', now()->endOfMonth()) ?? now()->endOfMonth();
            }
        } else {
            $timeId = 'y';
            $from   = now()->setYear($year)->startOfYear();
            $to     = (clone $from)->endOfYear();
        }
        session(['timeId' => $timeId, 'timeStart' => $from, 'timeEnd' => $to]);

        return [
            'id'   => session('timeId', 'y') ?? 'y',
            'from' => (session('timeStart', now()->setYear($year)->startOfYear()) ?? now()->setYear($year)->startOfYear())->toDateString(),
            'to'   => (session('timeEnd', now()->setYear($year)->endOfYear()) ?? now()->setYear($year)->endOfYear())->toDateString(),
        ];
    }
}
