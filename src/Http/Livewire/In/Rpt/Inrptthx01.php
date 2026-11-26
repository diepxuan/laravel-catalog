<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\In\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsINRptTHX01;
use Illuminate\Contracts\View\View;

/**
 * Tổng hợp hàng xuất (INRptTHX01) — 23 tham số.
 */
class Inrptthx01 extends AbstractReport
{
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    public string $pCtu1 = '';

    public string $pCtu2 = '';

    public string $pLoai_px = '';

    public string $pMa_kh = '';

    public string $pMa_Hd = '';

    public string $pMa_nx = '';

    public string $pMa_kho_xuat = '';

    public string $pMa_kho_nhap = '';

    public string $pMa_lo = '';

    public string $pMa_vt = '';

    public string $pNhom_VT = '';

    public string $pTK_VT = '1561';

    public string $pMa_bp = '';

    public string $pPSDC = '';

    public string $pMa_NT = 'VND';

    public string $pTk_Du = '';

    public string $pMa_phi = '';

    public string $pNgoai_te = '0';

    public string $pMa_spct = '';

    public string $pDVT = '0';

    protected function spClass(): string
    {
        return AsINRptTHX01::class;
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
            'pCtu1' => trim($this->pCtu1),
            'pCtu2' => trim($this->pCtu2),
            'pLoai_px' => trim($this->pLoai_px),
            'pMa_kh' => trim($this->pMa_kh),
            'pMa_Hd' => trim($this->pMa_Hd),
            'pMa_nx' => trim($this->pMa_nx),
            'pMa_kho_xuat' => trim($this->pMa_kho_xuat),
            'pMa_kho_nhap' => trim($this->pMa_kho_nhap),
            'pMa_lo' => trim($this->pMa_lo),
            'pMa_vt' => trim($this->pMa_vt),
            'pNhom_VT' => trim($this->pNhom_VT),
            'pTK_VT' => trim($this->pTK_VT),
            'pMa_bp' => trim($this->pMa_bp),
            'pPSDC' => trim($this->pPSDC),
            'pMa_NT' => trim($this->pMa_NT),
            'pTk_Du' => trim($this->pTk_Du),
            'pMa_phi' => trim($this->pMa_phi),
            'pNgoai_te' => trim($this->pNgoai_te),
            'pMa_spct' => trim($this->pMa_spct),
            'pDVT' => trim($this->pDVT),
        ];
    }

    public function render(): View
    {
        return view('catalog::in.rpt.inrptthx01', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
