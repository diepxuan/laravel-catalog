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
 * Concern: chỉ tiêu và báo cáo mua hàng trên PoCt1.
 *
 * Tách từ `Diepxuan\Simba\Models\PoCt1` (Phase 2 refactor) vì:
 * - Tổng hợp giá trị / số lượng theo vật tư, theo nhà cung cấp.
 * - Tỷ lệ nhập hàng (sl_nhap / so_luong).
 * - Báo cáo mua hàng (asPORptMH01, asPORptDH01, asPORptNH01).
 *
 * Tất cả là nghiệp vụ báo cáo, không thuộc schema Simba.
 */
trait HasPoCt1PurchaseMetrics
{
    /**
     * Tổng giá trị mua hàng theo vật tư.
     */
    public static function getTotalPurchaseByProduct(string $maCty, ?string $fromDate = null, ?string $toDate = null): Collection
    {
        $query = self::where('ma_cty', $maCty);

        if ($fromDate || $toDate) {
            $query->whereHas('poPh3', function ($q) use ($fromDate, $toDate): void {
                if ($fromDate) {
                    $q->whereDate('ngay_ct', '>=', $fromDate);
                }
                if ($toDate) {
                    $q->whereDate('ngay_ct', '<=', $toDate);
                }
            });
        }

        return $query->select('ma_vt', DB::raw('SUM(tien0) as tong_gia_tri'), DB::raw('SUM(tien_nt0) as tong_gia_tri_nt'))
            ->groupBy('ma_vt')
            ->get();
    }

    /**
     * Tổng số lượng mua theo vật tư.
     */
    public static function getTotalQuantityByProduct(string $maCty, ?string $fromDate = null, ?string $toDate = null): Collection
    {
        $query = self::where('ma_cty', $maCty);

        if ($fromDate || $toDate) {
            $query->whereHas('poPh3', function ($q) use ($fromDate, $toDate): void {
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
     * Tổng giá trị mua hàng theo nhà cung cấp.
     */
    public static function getTotalPurchaseBySupplier(string $maCty, ?string $fromDate = null, ?string $toDate = null): Collection
    {
        $query = self::where('ma_cty', $maCty);

        if ($fromDate || $toDate) {
            $query->whereHas('poPh3', function ($q) use ($fromDate, $toDate): void {
                if ($fromDate) {
                    $q->whereDate('ngay_ct', '>=', $fromDate);
                }
                if ($toDate) {
                    $q->whereDate('ngay_ct', '<=', $toDate);
                }
            });
        }

        return $query->select('poPh3.ma_ncc', DB::raw('SUM(po_ct1.tien0) as tong_gia_tri'), DB::raw('SUM(po_ct1.tien_nt0) as tong_gia_tri_nt'))
            ->join('po_ph3', 'po_ct1.stt_rec', '=', 'po_ph3.stt_rec')
            ->groupBy('po_ph3.ma_ncc')
            ->get();
    }

    /**
     * Tỷ lệ nhập hàng (sl_nhap / so_luong * 100).
     */
    public function getReceiptRate(): float
    {
        if ($this->so_luong == 0) {
            return 0;
        }

        return ($this->sl_nhap / $this->so_luong) * 100;
    }

    /**
     * Báo cáo mua hàng asPORptMH01.
     */
    public static function getPORptMH01(array $params): Collection
    {
        return self::executeRptSp('asPORptMH01', $params);
    }

    /**
     * Báo cáo đặt hàng asPORptDH01.
     */
    public static function getPORptDH01(array $params): Collection
    {
        return self::executeRptSp('asPORptDH01', $params);
    }

    /**
     * Báo cáo nhập hàng asPORptNH01.
     */
    public static function getPORptNH01(array $params): Collection
    {
        return self::executeRptSp('asPORptNH01', $params);
    }

    /**
     * Helper chung cho 3 báo cáo PO: cùng tập tham số.
     */
    protected static function executeRptSp(string $spName, array $params): Collection
    {
        return collect(DB::connection((new static())->getConnectionName())->select("EXEC {$spName}
            @pMa_Cty = :pMa_Cty,
            @pNgay_Ct1 = :pNgay_Ct1,
            @pNgay_Ct2 = :pNgay_Ct2,
            @pMa_Ncc = :pMa_Ncc,
            @pMa_Vt = :pMa_Vt,
            @pMa_Bp = :pMa_Bp,
            @pMa_Nt = :pMa_Nt
        ", [
            'pMa_Cty'   => $params['pMa_Cty']   ?? '',
            'pNgay_Ct1' => $params['pNgay_Ct1'] ?? '2025-01-01',
            'pNgay_Ct2' => $params['pNgay_Ct2'] ?? now(),
            'pMa_Ncc'   => $params['pMa_Ncc']   ?? '',
            'pMa_Vt'    => $params['pMa_Vt']    ?? '',
            'pMa_Bp'    => $params['pMa_Bp']    ?? '',
            'pMa_Nt'    => $params['pMa_Nt']    ?? 'VND',
        ]));
    }
}