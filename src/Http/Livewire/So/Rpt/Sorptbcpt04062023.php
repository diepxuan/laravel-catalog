<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\So\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsSORptBCPT04;
use Illuminate\Contracts\View\View;

/**
 * SORptBCPT04 — 28 tham so.
 */
class Sorptbcpt04062023 extends AbstractReport
{
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    public ?string $pMa_kh = null;

    public ?string $pMa_nhkh = null;

    public ?string $pMa_vt = null;

    public ?string $pMa_nhvt = null;

    public ?string $pMa_kho = null;

    public ?string $pMa_Bp = null;

    public ?string $pMa_Nvkd = null;

    public ?string $pMa_Hd = null;

    public ?string $pMa_NhHd = null;

    public ?string $pMa_plvt1 = null;

    public ?string $pMa_plvt2 = null;

    public ?string $pMa_plvt3 = null;

    public ?string $pkhuyen_mai = null;

    public ?string $pTra_ck = null;

    public ?string $pHang_ban = null;

    public ?string $pTra_lai = null;

    public ?string $pMa_NT = null;

    public ?string $pMa_plkh1 = null;

    public ?string $pMa_plkh2 = null;

    public ?string $pMa_plkh3 = null;

    public ?string $pMa_httt = null;

    public ?string $pMa_TT = null;

    public ?string $pMa_vitri = null;

    public ?string $pMa_lo = null;

    public ?string $pType = null;

    protected function spClass(): string
    {
        return AsSORptBCPT04::class;
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
            'pMa_vt' => $this->pMa_vt,
            'pMa_nhvt' => $this->pMa_nhvt,
            'pMa_kho' => $this->pMa_kho,
            'pMa_Bp' => $this->pMa_Bp,
            'pMa_Nvkd' => $this->pMa_Nvkd,
            'pMa_Hd' => $this->pMa_Hd,
            'pMa_NhHd' => $this->pMa_NhHd,
            'pMa_plvt1' => $this->pMa_plvt1,
            'pMa_plvt2' => $this->pMa_plvt2,
            'pMa_plvt3' => $this->pMa_plvt3,
            'pkhuyen_mai' => $this->pkhuyen_mai,
            'pTra_ck' => $this->pTra_ck,
            'pHang_ban' => $this->pHang_ban,
            'pTra_lai' => $this->pTra_lai,
            'pMa_NT' => $this->pMa_NT,
            'pMa_plkh1' => $this->pMa_plkh1,
            'pMa_plkh2' => $this->pMa_plkh2,
            'pMa_plkh3' => $this->pMa_plkh3,
            'pMa_httt' => $this->pMa_httt,
            'pMa_TT' => $this->pMa_TT,
            'pMa_vitri' => $this->pMa_vitri,
            'pMa_lo' => $this->pMa_lo,
            'pType' => $this->pType,
        ];
    }

    public function render(): View
    {
        return view('catalog::so.rpt.sorptbcpt04062023', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
