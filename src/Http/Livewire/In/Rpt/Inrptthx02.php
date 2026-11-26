<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\In\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsINRptTHX02;
use Illuminate\Contracts\View\View;

/**
 * Tổng hợp hàng xuất theo hai chỉ tiêu (INRptTHX02) — 28 tham số.
 */
class Inrptthx02 extends AbstractReport
{
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    public ?string $pCtu1 = null;

    public ?string $pCtu2 = null;

    public ?string $pLoai_px = null;

    public ?string $pLoai_gd = null;

    public ?string $pGroup = null;

    public ?string $pDetail = null;

    public ?string $pMa_kh = null;

    public ?string $pMa_nhkh = null;

    public ?string $pMa_Hd = null;

    public ?string $pMa_dangnx = null;

    public ?string $pMa_kho = null;

    public ?string $pMa_khon = null;

    public ?string $pMa_vitri = null;

    public ?string $pMa_vt = null;

    public ?string $pNhom_VT = null;

    public ?string $pTK_VT = null;

    public ?string $pMa_bp = null;

    public ?string $pPSDC = null;

    public ?string $pMa_NT = null;

    public ?string $pTk_Du = null;

    public ?string $pMa_lo = null;

    public ?string $pMa_phi = null;

    public ?string $pNgoai_te = null;

    public ?string $pMa_spct = null;

    public ?string $pDVT = null;

    protected function spClass(): string
    {
        return AsINRptTHX02::class;
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
            'pLoai_px' => $this->pLoai_px,
            'pLoai_gd' => $this->pLoai_gd,
            'pGroup' => $this->pGroup,
            'pDetail' => $this->pDetail,
            'pMa_kh' => $this->pMa_kh,
            'pMa_nhkh' => $this->pMa_nhkh,
            'pMa_Hd' => $this->pMa_Hd,
            'pMa_dangnx' => $this->pMa_dangnx,
            'pMa_kho' => $this->pMa_kho,
            'pMa_khon' => $this->pMa_khon,
            'pMa_vitri' => $this->pMa_vitri,
            'pMa_vt' => $this->pMa_vt,
            'pNhom_VT' => $this->pNhom_VT,
            'pTK_VT' => $this->pTK_VT,
            'pMa_bp' => $this->pMa_bp,
            'pPSDC' => $this->pPSDC,
            'pMa_NT' => $this->pMa_NT,
            'pTk_Du' => $this->pTk_Du,
            'pMa_lo' => $this->pMa_lo,
            'pMa_phi' => $this->pMa_phi,
            'pNgoai_te' => $this->pNgoai_te,
            'pMa_spct' => $this->pMa_spct,
            'pDVT' => $this->pDVT,
        ];
    }

    public function render(): View
    {
        return view('catalog::in.rpt.inrptthx02', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
