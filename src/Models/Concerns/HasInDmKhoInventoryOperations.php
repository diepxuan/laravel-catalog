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

/**
 * Concern: các thao tác nghiệp vụ tồn kho trên InDmKho.
 *
 * Tách từ `Diepxuan\Simba\Models\InDmKho` (Phase 2 refactor) vì:
 * - Gọi stored procedure AsINRptCD02 để lấy tồn kho theo vật tư / theo nhóm.
 * - Tính giá trị tồn kho từ kết quả báo cáo (tổng sl_ton * gia_nt2).
 *
 * Bản thân các method này dùng `$this->ma_cty` và `$this->ma_kho` từ Model,
 * nhưng tổng hợp dữ liệu và quyết định nghiệp vụ báo cáo tồn kho thuộc
 * Catalog, không thuộc schema Simba thuần.
 */
trait HasInDmKhoInventoryOperations
{
    /**
     * Lấy tồn kho theo vật tư.
     */
    public function getInventoryByProduct(string $maVt, ?string $ngay = null): array
    {
        $result = \Diepxuan\Simba\StoredProcedures\AsINRptCD02::call([
            'pMa_Cty'    => $this->ma_cty,
            'pMa_vt'     => $maVt,
            'pMa_kho'    => $this->ma_kho,
            'pNgay'      => $ngay ?? date('Y-m-d'),
            'pMa_vitri'  => null,
            'pTk_vt'     => null,
            'pMa_nhvt'   => null,
            'pDVT'       => null,
            'pNgoai_te'  => null,
            'pDk_Ck'     => null,
            'pMa_lo'     => null,
            'pQuaToiThieu' => null,
            'pQuaToiDa'    => null,
            'pSysMsg1'   => null,
        ]);

        return $result->isNotEmpty() ? (array) $result->first() : [];
    }

    /**
     * Lấy danh sách vật tư tồn kho theo kho.
     */
    public function getInventoryList(?string $maNhvt = null, ?string $ngay = null): Collection
    {
        return \Diepxuan\Simba\StoredProcedures\AsINRptCD02::call([
            'pMa_Cty'   => $this->ma_cty,
            'pMa_kho'   => $this->ma_kho,
            'pMa_nhvt'  => $maNhvt ?? '',
            'pNgay'     => $ngay ?? date('Y-m-d'),
            'pMa_vt'    => null,
            'pMa_vitri' => null,
            'pTk_vt'    => null,
            'pDVT'      => null,
            'pNgoai_te' => null,
            'pDk_Ck'    => null,
            'pMa_lo'    => null,
            'pQuaToiThieu' => null,
            'pQuaToiDa'    => null,
            'pSysMsg1'  => null,
        ]);
    }

    /**
     * Tính giá trị tồn kho theo kho.
     */
    public function getInventoryValue(?string $ngay = null): float
    {
        $inventoryList = $this->getInventoryList(null, $ngay);

        $totalValue = 0;
        foreach ($inventoryList as $item) {
            $totalValue += ($item->sl_ton ?? 0) * ($item->gia_nt2 ?? 0);
        }

        return $totalValue;
    }
}