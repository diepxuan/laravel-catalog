<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 *
 * @lastupdate 2026-06-22
 */

namespace Diepxuan\Catalog\Models\Concerns;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Concern: chỉ tiêu và báo cáo bán hàng trên SoCt1.
 *
 * Tách từ `Diepxuan\Simba\Models\SoCt1` (Phase 2 refactor) vì:
 * - Tổng doanh thu / số lượng theo vật tư / theo nhân viên kinh doanh.
 * - Báo cáo bán hàng (asSORptBH01, asSORptDT01, asSORptSL01).
 *
 * Tất cả là nghiệp vụ báo cáo, không thuộc schema Simba.
 */
trait HasSoCt1SalesMetrics
{
    /**
     * Tổng doanh thu theo vật tư.
     */
    public static function getTotalRevenueByProduct(string $maCty, ?string $fromDate = null, ?string $toDate = null): Collection
    {
        $query = self::where('ma_cty', $maCty);

        if ($fromDate || $toDate) {
            $query->whereHas('soPh3', function ($q) use ($fromDate, $toDate): void {
                if ($fromDate) {
                    $q->whereDate('ngay_ct', '>=', $fromDate);
                }
                if ($toDate) {
                    $q->whereDate('ngay_ct', '<=', $toDate);
                }
            });
        }

        return $query->select('ma_vt', DB::raw('SUM(tien2) as tong_doanh_thu'), DB::raw('SUM(tien_nt2) as tong_doanh_thu_nt'))
            ->groupBy('ma_vt')
            ->get();
    }

    /**
     * Tổng số lượng bán theo vật tư.
     */
    public static function getTotalQuantityByProduct(string $maCty, ?string $fromDate = null, ?string $toDate = null): Collection
    {
        $query = self::where('ma_cty', $maCty);

        if ($fromDate || $toDate) {
            $query->whereHas('soPh3', function ($q) use ($fromDate, $toDate): void {
                if ($fromDate) {
                    $q->whereDate('ngay_ct', '>=', $fromDate);
                }
                if ($toDate) {
                    $q->whereDate('ngay_ct', '<=', $toDate);
                }
            });
        }

        return $query->select('ma_vt', DB::raw('SUM(so_luong) as tong_so_luong'), DB::raw('SUM(so_luong_qd) as tong_so_luong_qd'))
            ->groupBy('ma_vt')
            ->get();
    }

    /**
     * Tổng doanh thu theo nhân viên kinh doanh.
     */
    public static function getTotalRevenueBySalesperson(string $maCty, ?string $fromDate = null, ?string $toDate = null): Collection
    {
        $query = self::where('ma_cty', $maCty)
            ->whereNotNull('ma_nvkd');

        if ($fromDate || $toDate) {
            $query->whereHas('soPh3', function ($q) use ($fromDate, $toDate): void {
                if ($fromDate) {
                    $q->whereDate('ngay_ct', '>=', $fromDate);
                }
                if ($toDate) {
                    $q->whereDate('ngay_ct', '<=', $toDate);
                }
            });
        }

        return $query->select('ma_nvkd', DB::raw('SUM(tien2) as tong_doanh_thu'), DB::raw('SUM(tien_nt2) as tong_doanh_thu_nt'))
            ->groupBy('ma_nvkd')
            ->get();
    }

    /**
     * Báo cáo bán hàng asSORptBH01.
     */
    public static function getSORptBH01(array $params): Collection
    {
        return self::executeSORptSp('asSORptBH01', $params, [
            'pMa_Cty', 'pNgay_Ct1', 'pNgay_Ct2', 'pMa_Kh', 'pMa_Vt', 'pMa_Nvkd', 'pMa_Bp', 'pMa_Nt',
        ]);
    }

    /**
     * Báo cáo doanh thu asSORptDT01.
     */
    public static function getSORptDT01(array $params): Collection
    {
        return self::executeSORptSp('asSORptDT01', $params, [
            'pMa_Cty', 'pNgay_Ct1', 'pNgay_Ct2', 'pMa_Vt', 'pMa_Nvkd', 'pMa_Bp', 'pMa_Nt',
        ]);
    }

    /**
     * Báo cáo số lượng asSORptSL01.
     */
    public static function getSORptSL01(array $params): Collection
    {
        return self::executeSORptSp('asSORptSL01', $params, [
            'pMa_Cty', 'pNgay_Ct1', 'pNgay_Ct2', 'pMa_Vt', 'pMa_Nvkd', 'pMa_Bp',
        ]);
    }

    /**
     * Helper chung cho 3 báo cáo SO: cùng tập tham số cơ bản + tuỳ sp.
     */
    protected static function executeSORptSp(string $spName, array $params, array $paramNames, array $defaults = []): Collection
    {
        $bindings = [];
        $sql      = "EXEC {$spName}\n";
        $paramLines = [];
        foreach ($paramNames as $p) {
            $paramLines[] = "        @$p = :$p";
            $default      = $defaults[$p] ?? match ($p) {
                'pNgay_Ct1' => '2025-01-01',
                'pNgay_Ct2' => now(),
                'pMa_Nt'    => 'VND',
                default     => '',
            };
            $bindings[$p] = $params[$p] ?? $default;
        }
        $sql .= implode(",\n", $paramLines) . "\n        ";

        return collect(DB::connection((new static())->getConnectionName())->select($sql, $bindings));
    }
}