<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\So\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsARRptBK01;
use Illuminate\Contracts\View\View;

/**
 * Bảng kê công nợ 01 (ARRptBK01) — 19 tham so.
 */
class Arrptbk01 extends AbstractReport
{
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    public ?string $pMa_kh = null;

    public ?string $pMa_vt = null;

    public ?string $pMa_nhvt = null;

    public ?string $pMa_kho = null;

    public ?string $pMa_lo = null;

    public ?string $pMa_bp = null;

    public ?string $pMa_hd = null;

    public ?string $pMa_phi = null;

    public ?string $pMa_spct = null;

    public ?string $pMa_nvkd = null;

    public ?string $pMa_nt = null;

    public ?string $pSoct1 = null;

    public ?string $pSoct2 = null;

    public ?string $pMa_plkh1 = null;

    public ?string $pMa_plkh2 = null;

    public ?string $pMa_plkh3 = null;

    protected function spClass(): string
    {
        return AsARRptBK01::class;
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
            'pMa_kh' => $this->pMa_kh,
            'pMa_vt' => $this->pMa_vt,
            'pMa_nhvt' => $this->pMa_nhvt,
            'pMa_kho' => $this->pMa_kho,
            'pMa_lo' => $this->pMa_lo,
            'pMa_bp' => $this->pMa_bp,
            'pMa_hd' => $this->pMa_hd,
            'pMa_phi' => $this->pMa_phi,
            'pMa_spct' => $this->pMa_spct,
            'pMa_nvkd' => $this->pMa_nvkd,
            'pMa_nt' => $this->pMa_nt,
            'pSoct1' => $this->pSoct1,
            'pSoct2' => $this->pSoct2,
            'pMa_plkh1' => $this->pMa_plkh1,
            'pMa_plkh2' => $this->pMa_plkh2,
            'pMa_plkh3' => $this->pMa_plkh3,
        ];
    }

    public function render(): View
    {
        return view('catalog::so.rpt.arrptbk01', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
