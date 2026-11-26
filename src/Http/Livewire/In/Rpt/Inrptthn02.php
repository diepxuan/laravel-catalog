<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\In\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsINRptTHN02;
use Illuminate\Contracts\View\View;

/**
 * Tổng hợp hàng nhập theo hai chỉ tiêu (INRptTHN02) — 29 tham số.
 */
class Inrptthn02 extends AbstractReport
{
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    public ?string $pCtu1 = null;

    public ?string $pCtu2 = null;

    public ?string $pGroup = null;

    public ?string $pDetail = null;

    public ?string $pMa_kh = null;

    public ?string $pMa_NhKH = null;

    public ?string $pMa_PLKH1 = null;

    public ?string $pMa_PLKH2 = null;

    public ?string $pMa_PLKH3 = null;

    public ?string $pLoai_pn = null;

    public ?string $pLoai_gd = null;

    public ?string $pMa_Hd = null;

    public ?string $pMa_vt = null;

    public ?string $pMa_kho = null;

    public ?string $pMa_vitri = null;

    public ?string $pMa_bp = null;

    public ?string $pMa_spct = null;

    public ?string $pTK_VT = null;

    public ?string $pTk_Du = null;

    public ?string $pNhom_VT = null;

    public ?string $pPSDC = null;

    public ?string $pMa_nx = null;

    public ?string $pMa_lo = null;

    public ?string $pMa_phi = null;

    public ?string $pMa_NT = null;

    public ?string $pDVT = null;

    protected function spClass(): string
    {
        return AsINRptTHN02::class;
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
            'pCtu1' => $this->pCtu1,
            'pCtu2' => $this->pCtu2,
            'pGroup' => (string) $this->pGroup ?? '',
            'pDetail' => (string) $this->pDetail ?? '',
            'pMa_kh' => $this->pMa_kh,
            'pMa_NhKH' => $this->pMa_NhKH,
            'pMa_PLKH1' => $this->pMa_PLKH1,
            'pMa_PLKH2' => $this->pMa_PLKH2,
            'pMa_PLKH3' => $this->pMa_PLKH3,
            'pLoai_pn' => $this->pLoai_pn,
            'pLoai_gd' => $this->pLoai_gd,
            'pMa_Hd' => $this->pMa_Hd,
            'pMa_vt' => $this->pMa_vt,
            'pMa_kho' => $this->pMa_kho,
            'pMa_vitri' => $this->pMa_vitri,
            'pMa_bp' => $this->pMa_bp,
            'pMa_spct' => $this->pMa_spct,
            'pTK_VT' => $this->pTK_VT,
            'pTk_Du' => $this->pTk_Du,
            'pNhom_VT' => $this->pNhom_VT,
            'pPSDC' => $this->pPSDC,
            'pMa_nx' => $this->pMa_nx,
            'pMa_lo' => $this->pMa_lo,
            'pMa_phi' => $this->pMa_phi,
            'pMa_NT' => $this->pMa_NT,
            'pDVT' => $this->pDVT,
        ];
    }

    public function render(): View
    {
        return view('catalog::in.rpt.inrptthn02', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
