<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\In\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsINRptCD01;
use Illuminate\Contracts\View\View;

/**
 * Nhập xuất tồn: Số lượng và giá trị (INRptCD01).
 *
 * Port từ INRptCD01.dll (menu 14.20.08 — 1 menu duy nhất, không suffix).
 * SP `asINRptCD01` — 18 tham số, đối chiếu simba-docs/procedures/IN.
 */
class Inrptcd01 extends AbstractReport
{
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    /** Loại báo cáo: chi tiết / tổng hợp. */
    public string $pLoai_bc = '';

    public string $pMa_vt = '';

    public string $pTk_vt = '1561';

    public string $pMa_nhvt = '';

    public string $pMa_kho = '';

    public string $pMa_vitri = '';

    public string $pma_plvt1 = '';

    public string $pma_plvt2 = '';

    public string $pma_plvt3 = '';

    public string $group1 = '';

    public string $group2 = '';

    public string $group3 = '';

    /** Đơn vị tính: 0 = gốc, 1 = nhỏ nhất, 2 = lớn nhất. */
    public string $pDVT = '0';

    public string $pPSDC = '';

    /** Ngoại tệ: 0 = VND, 1 = ngoại tệ. */
    public string $pNgoai_te = '0';

    protected function spClass(): string
    {
        return AsINRptCD01::class;
    }

    /**
     * @return array<string,mixed>
     */
    public function procedurePayload(): array
    {
        return [
            'pMa_cty' => (string) \CatalogService::company()->id,
            'pNgay1' => $this->pNgay1,
            'pNgay2' => $this->pNgay2,
            'pLoai_bc' => trim($this->pLoai_bc),
            'pMa_vt' => trim($this->pMa_vt),
            'pTk_vt' => trim($this->pTk_vt),
            'pMa_nhvt' => trim($this->pMa_nhvt),
            'pMa_kho' => trim($this->pMa_kho),
            'pMa_vitri' => trim($this->pMa_vitri),
            'pma_plvt1' => trim($this->pma_plvt1),
            'pma_plvt2' => trim($this->pma_plvt2),
            'pma_plvt3' => trim($this->pma_plvt3),
            'group1' => $this->group1,
            'group2' => $this->group2,
            'group3' => $this->group3,
            'pDVT' => $this->pDVT,
            'pPSDC' => trim($this->pPSDC),
            'pNgoai_te' => $this->pNgoai_te,
        ];
    }

    public function render(): View
    {
        return view('catalog::in.rpt.inrptcd01', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
