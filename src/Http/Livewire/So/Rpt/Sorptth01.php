<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\So\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsSORptTH01;
use Illuminate\Contracts\View\View;

/**
 * SORptTH01 — 34 tham so.
 */
class Sorptth01 extends AbstractReport
{
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    public ?string $pMa_kh = null;

    public ?string $pMa_nhkh = null;

    public ?string $pMa_plkh1 = null;

    public ?string $pMa_plkh2 = null;

    public ?string $pMa_plkh3 = null;

    public ?string $pMa_plvt1 = null;

    public ?string $pMa_plvt2 = null;

    public ?string $pMa_plvt3 = null;

    public ?string $pMa_hd = null;

    public ?string $pMa_nhhd = null;

    public ?string $pMa_kho = null;

    public ?string $pMa_vt = null;

    public ?string $pMa_nhvt = null;

    public ?string $pHang_ban = null;

    public ?string $pkhuyen_mai = null;

    public ?string $pTra_ck = null;

    public ?string $pTra_lai = null;

    public ?string $pMa_NT = null;

    public ?string $pMa_ct = null;

    public ?string $pTrang_thai = null;

    public ?string $pMa_lo = null;

    public ?string $pMa_vitri = null;

    public ?string $pMa_httt = null;

    public ?string $pMa_TT = null;

    public ?string $pMa_bp = null;

    public ?string $pMa_nvkd = null;

    public ?string $pMa_spct = null;

    public ?string $pSysMsg1 = null;

    public ?string $ParamDefines = null;

    public ?string $pMa_nt = null;

    protected function spClass(): string
    {
        return AsSORptTH01::class;
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
            'pMa_nhkh' => $this->pMa_nhkh,
            'pMa_plkh1' => $this->pMa_plkh1,
            'pMa_plkh2' => $this->pMa_plkh2,
            'pMa_plkh3' => $this->pMa_plkh3,
            'pMa_plvt1' => $this->pMa_plvt1,
            'pMa_plvt2' => $this->pMa_plvt2,
            'pMa_plvt3' => $this->pMa_plvt3,
            'pMa_hd' => $this->pMa_hd,
            'pMa_nhhd' => $this->pMa_nhhd,
            'pMa_kho' => $this->pMa_kho,
            'pMa_vt' => $this->pMa_vt,
            'pMa_nhvt' => $this->pMa_nhvt,
            'pHang_ban' => $this->pHang_ban,
            'pkhuyen_mai' => $this->pkhuyen_mai,
            'pTra_ck' => $this->pTra_ck,
            'pTra_lai' => $this->pTra_lai,
            'pMa_NT' => $this->pMa_NT,
            'pMa_ct' => $this->pMa_ct,
            'pTrang_thai' => $this->pTrang_thai,
            'pMa_lo' => $this->pMa_lo,
            'pMa_vitri' => $this->pMa_vitri,
            'pMa_httt' => $this->pMa_httt,
            'pMa_TT' => $this->pMa_TT,
            'pMa_bp' => $this->pMa_bp,
            'pMa_nvkd' => $this->pMa_nvkd,
            'pMa_spct' => $this->pMa_spct,
            'pSysMsg1' => $this->pSysMsg1,
            'ParamDefines' => $this->ParamDefines,
            'pMa_Cty' => (string) \CatalogService::company()->id,
            'pMa_nt' => $this->pMa_nt,
        ];
    }

    public function render(): View
    {
        return view('catalog::so.rpt.sorptth01', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
