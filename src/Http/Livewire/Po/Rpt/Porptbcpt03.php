<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Po\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsPORptBCPT03;
use Illuminate\Contracts\View\View;

/**
 * Báo cáo phân tích mua hàng (PORptBCPT03) — 26 tham so theo form goc.
 */
class Porptbcpt03 extends AbstractReport
{
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    public ?string $pMa_kh = null;

    public ?string $pMa_nhkh = null;

    public ?string $pMa_plkh1 = null;

    public ?string $pMa_plkh2 = null;

    public ?string $pMa_plkh3 = null;

    public ?string $pMa_hd = null;

    public ?string $pMa_nhhd = null;

    public ?string $pMa_HTTT = null;


    public ?string $pMa_kho = null;

    public ?string $pMa_vitri = null;

    public ?string $pMa_lo = null;

    public ?string $pMa_phi = null;

    public ?string $pMa_vt = null;

    public ?string $pMa_nhvt = null;

    public ?string $pMa_plvt1 = null;

    public ?string $pMa_plvt2 = null;

    public ?string $pMa_plvt3 = null;

    public ?string $pMa_spct = null;

    public ?string $pTra_lai = null;

    public ?string $pMa_Nt = null;

    public ?string $pGroup = null;

    public ?string $pDetail = null;

    protected function spClass(): string
    {
        return AsPORptBCPT03::class;
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
            'pMa_hd' => $this->pMa_hd,
            'pMa_nhhd' => $this->pMa_nhhd,
            'pMa_HTTT' => $this->pMa_HTTT,
            'pMa_kho' => $this->pMa_kho,
            'pMa_vitri' => $this->pMa_vitri,
            'pMa_lo' => $this->pMa_lo,
            'pMa_phi' => $this->pMa_phi,
            'pMa_vt' => $this->pMa_vt,
            'pMa_nhvt' => $this->pMa_nhvt,
            'pMa_plvt1' => $this->pMa_plvt1,
            'pMa_plvt2' => $this->pMa_plvt2,
            'pMa_plvt3' => $this->pMa_plvt3,
            'pMa_spct' => $this->pMa_spct,
            'pTra_lai' => $this->pTra_lai,
            'pMa_Nt' => $this->pMa_Nt,
            'pGroup' => $this->pGroup,
            'pDetail' => $this->pDetail,
        ];
    }

    public function render(): View
    {
        return view('catalog::po.rpt.porptbcpt03', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
