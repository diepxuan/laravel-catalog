<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\In\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsINRptBKX04;
use Illuminate\Contracts\View\View;

/**
 * INRptBKX04 — 21 tham so.
 */
class Inrptbkx04 extends AbstractReport
{
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    public ?string $pGroupType = null;

    public ?string $pSoct1 = null;

    public ?string $pSoct2 = null;

    public ?string $pMa_kh = null;

    public ?string $pMa_Hd = null;

    public ?string $pMa_kho = null;

    public ?string $pMa_spct = null;

    public ?string $pMa_bp = null;

    public ?string $pLoai_px = null;

    public ?string $pLoai_gd = null;

    public ?string $pMa_Nt = null;

    public ?string $pMa_vt = null;

    public ?string $PMa_nhvt = null;

    public ?string $ptk_vt = null;

    public ?string $pTk_Du = null;

    public ?string $pMa_lo = null;

    public ?string $pMa_phi = null;

    public ?string $pDVT = null;

    protected function spClass(): string
    {
        return AsINRptBKX04::class;
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
            'pGroupType' => $this->pGroupType,
            'pSoct1' => $this->pSoct1,
            'pSoct2' => $this->pSoct2,
            'pMa_kh' => $this->pMa_kh,
            'pMa_Hd' => $this->pMa_Hd,
            'pMa_kho' => $this->pMa_kho,
            'pMa_spct' => $this->pMa_spct,
            'pMa_bp' => $this->pMa_bp,
            'pLoai_px' => $this->pLoai_px,
            'pLoai_gd' => $this->pLoai_gd,
            'pMa_Nt' => $this->pMa_Nt,
            'pMa_vt' => $this->pMa_vt,
            'PMa_nhvt' => $this->PMa_nhvt,
            'ptk_vt' => $this->ptk_vt,
            'pTk_Du' => $this->pTk_Du,
            'pMa_lo' => $this->pMa_lo,
            'pMa_phi' => $this->pMa_phi,
            'pDVT' => $this->pDVT,
        ];
    }

    public function render(): View
    {
        return view('catalog::in.rpt.inrptbkx04', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
