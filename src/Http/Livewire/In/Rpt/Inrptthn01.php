<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\In\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsINRptTHN01;
use Illuminate\Contracts\View\View;

/**
 * Tổng hợp hàng nhập (INRptTHN01) — menu 14.20.17.
 *
 * 27 tham số theo simba-docs/procedures/IN.
 * Route có suffix 142017 vì sysMenu có 3 menu active cùng DLL.
 */
class Inrptthn01 extends AbstractReport
{
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    public string $pCtu1 = '';

    public string $pCtu2 = '';

    public string $pMa_kh = '';

    public string $pMa_NhKH = '';

    public string $pMa_PLKH1 = '';

    public string $pMa_PLKH2 = '';

    public string $pMa_PLKH3 = '';

    public string $pLoai_pn = '';

    public string $pLoai_gd = '';

    public string $pMa_Hd = '';

    public string $pMa_vt = '';

    public string $pMa_kho = '';

    public string $pMa_vitri = '';

    public string $pMa_bp = '';

    public string $pMa_spct = '';

    public string $pTK_VT = '1561';

    public string $pTk_Du = '';

    public string $pNhom_VT = '';

    public string $pPSDC = '';

    public string $pMa_nx = '';

    public string $pMa_lo = '';

    public string $pMa_phi = '';

    public string $pMa_NT = 'VND';

    public string $pDVT = '0';

    protected function spClass(): string
    {
        return AsINRptTHN01::class;
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
            'pMa_kh' => trim($this->pMa_kh),
            'pMa_NhKH' => trim($this->pMa_NhKH),
            'pMa_PLKH1' => trim($this->pMa_PLKH1),
            'pMa_PLKH2' => trim($this->pMa_PLKH2),
            'pMa_PLKH3' => trim($this->pMa_PLKH3),
            'pLoai_pn' => trim($this->pLoai_pn),
            'pLoai_gd' => trim($this->pLoai_gd),
            'pMa_Hd' => trim($this->pMa_Hd),
            'pMa_vt' => trim($this->pMa_vt),
            'pMa_kho' => trim($this->pMa_kho),
            'pMa_vitri' => trim($this->pMa_vitri),
            'pMa_bp' => trim($this->pMa_bp),
            'pMa_spct' => trim($this->pMa_spct),
            'pTK_VT' => trim($this->pTK_VT),
            'pTk_Du' => trim($this->pTk_Du),
            'pNhom_VT' => trim($this->pNhom_VT),
            'pPSDC' => trim($this->pPSDC),
            'pMa_nx' => trim($this->pMa_nx),
            'pMa_lo' => trim($this->pMa_lo),
            'pMa_phi' => trim($this->pMa_phi),
            'pMa_NT' => trim($this->pMa_NT),
            'pDVT' => trim($this->pDVT),
        ];
    }

    public function render(): View
    {
        return view('catalog::in.rpt.inrptthn01', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
