<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Po\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsPORptBK01;
use Illuminate\Contracts\View\View;

/**
 * PORptBK01 — 29 tham so (da bo system params).
 */
class Porptbk01 extends AbstractReport
{
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    public ?string $pTrang_thai = null;

    public ?string $pSoct1 = null;

    public ?string $pSoct2 = null;

    public ?string $pMa_kh = null;

    public ?string $pMa_nhkh = null;

    public ?string $pMa_vt = null;

    public ?string $pMa_nhvt = null;

    public ?string $pMa_kho = null;

    public ?string $pMa_vitri = null;

    public ?string $pMa_nvkd = null;

    public ?string $pMa_nt = null;

    public ?string $pMa_hd = null;

    public ?string $pMa_plkh1 = null;

    public ?string $pMa_plkh2 = null;

    public ?string $pMa_plkh3 = null;

    public ?string $pMa_ct = null;

    public ?string $pMa_lo = null;

    public ?string $pMa_httt = null;

    public ?string $pMa_TT = null;

    public ?string $pMa_bp = null;

    public ?string $pMa_spct = null;

    public ?string $pMa_plvt1 = null;

    public ?string $pMa_plvt2 = null;

    public ?string $pMa_plvt3 = null;

    public ?string $pMa_nhhd = null;

    public ?string $pMa_Phi = null;

    protected function spClass(): string
    {
        return AsPORptBK01::class;
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
            'pTrang_thai' => $this->pTrang_thai,
            'pSoct1' => $this->pSoct1,
            'pSoct2' => $this->pSoct2,
            'pMa_kh' => $this->pMa_kh,
            'pMa_nhkh' => $this->pMa_nhkh,
            'pMa_vt' => $this->pMa_vt,
            'pMa_nhvt' => $this->pMa_nhvt,
            'pMa_kho' => $this->pMa_kho,
            'pMa_vitri' => $this->pMa_vitri,
            'pMa_nvkd' => $this->pMa_nvkd,
            'pMa_nt' => $this->pMa_nt,
            'pMa_hd' => $this->pMa_hd,
            'pMa_plkh1' => $this->pMa_plkh1,
            'pMa_plkh2' => $this->pMa_plkh2,
            'pMa_plkh3' => $this->pMa_plkh3,
            'pMa_ct' => $this->pMa_ct,
            'pMa_lo' => $this->pMa_lo,
            'pMa_httt' => $this->pMa_httt,
            'pMa_TT' => $this->pMa_TT,
            'pMa_bp' => $this->pMa_bp,
            'pMa_spct' => $this->pMa_spct,
            'pMa_plvt1' => $this->pMa_plvt1,
            'pMa_plvt2' => $this->pMa_plvt2,
            'pMa_plvt3' => $this->pMa_plvt3,
            'pMa_nhhd' => $this->pMa_nhhd,
            'pMa_Phi' => $this->pMa_Phi,
        ];
    }

    public function render(): View
    {
        return view('catalog::po.rpt.porptbk01', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
