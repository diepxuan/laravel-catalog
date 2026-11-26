<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\In\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsINRptBKN04;
use Illuminate\Contracts\View\View;

/**
 * Bang ke phieu nhap (INRptBKN04) — 23 tham so theo form goc.
 */
class Inrptbkn04 extends AbstractReport
{
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    public string $pSoct1 = '';

    public string $pSoct2 = '';

    public string $pLoai_pn = '';

    public string $pMa_Hd = '';

    public string $pMa_kh = '';

    public string $pMa_vt = '';

    public string $pMa_kho = '';

    public string $pMa_vitri = '';

    public string $pMa_bp = '';

    public string $pMa_nx = '';

    public string $pTK_VT = '1561';

    public string $pTK_Du = '';

    public string $pMa_NhVT = '';

    public string $pMa_lo = '';

    public string $pMa_phi = '';

    public string $pMa_spct = '';

    public string $pMa_NT = 'VND';

    public string $pDVT = '0';

    public string $pGroupType = '';

    public string $pLoai_gd = '';

    protected function spClass(): string
    {
        return AsINRptBKN04::class;
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
            'pSoct1' => trim($this->pSoct1),
            'pSoct2' => trim($this->pSoct2),
            'pLoai_pn' => trim($this->pLoai_pn),
            'pMa_Hd' => trim($this->pMa_Hd),
            'pMa_kh' => trim($this->pMa_kh),
            'pMa_vt' => trim($this->pMa_vt),
            'pMa_kho' => trim($this->pMa_kho),
            'pMa_vitri' => trim($this->pMa_vitri),
            'pMa_bp' => trim($this->pMa_bp),
            'pMa_nx' => trim($this->pMa_nx),
            'pTK_VT' => trim($this->pTK_VT),
            'pTK_Du' => trim($this->pTK_Du),
            'pMa_NhVT' => trim($this->pMa_NhVT),
            'pMa_lo' => trim($this->pMa_lo),
            'pMa_phi' => trim($this->pMa_phi),
            'pMa_spct' => trim($this->pMa_spct),
            'pMa_NT' => trim($this->pMa_NT),
            'pDVT' => trim($this->pDVT),
            'pGroupType' => trim($this->pGroupType),
            'pLoai_gd' => trim($this->pLoai_gd),
        ];
    }

    public function render(): View
    {
        return view('catalog::in.rpt.inrptbkn04', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
