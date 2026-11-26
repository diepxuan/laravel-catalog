<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Gl\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsGLRptBK01;
use Illuminate\Contracts\View\View;

/**
 * Bảng kê chứng từ ghi sổ (GLRptBK01) — 19 tham so.
 *
 * Docs va wrapper khop hoan toan.
 */
class Glrptbk01 extends AbstractReport
{
    public ?string $pngay_ct1 = null;

    public ?string $pngay_ct2 = null;

    public ?string $pso_ct1 = null;

    public ?string $pso_ct2 = null;

    public ?string $ptk = null;

    public ?string $pNo_co = null;

    public ?string $ptk_du = null;

    public ?string $pma_kh = null;

    public ?string $pMa_Nhkh = null;

    public ?string $pma_bp = null;

    public ?string $pma_phi = null;

    public ?string $pma_hd = null;

    public ?string $pma_ku = null;

    public ?string $pma_spct = null;

    public ?string $pma_lo = null;

    public ?string $pma_ct = null;

    public ?string $pdien_giai = null;

    public ?string $pma_nt = null;

    protected function spClass(): string
    {
        return AsGLRptBK01::class;
    }

    /**
     * @return array<string,mixed>
     */
    public function procedurePayload(): array
    {
        return [
            'pma_cty' => (string) \CatalogService::company()->id,
            'pngay_ct1' => $this->pngay_ct1,
            'pngay_ct2' => $this->pngay_ct2,
            'pso_ct1' => $this->pso_ct1,
            'pso_ct2' => $this->pso_ct2,
            'ptk' => $this->ptk,
            'pNo_co' => $this->pNo_co,
            'ptk_du' => $this->ptk_du,
            'pma_kh' => $this->pma_kh,
            'pMa_Nhkh' => $this->pMa_Nhkh,
            'pma_bp' => $this->pma_bp,
            'pma_phi' => $this->pma_phi,
            'pma_hd' => $this->pma_hd,
            'pma_ku' => $this->pma_ku,
            'pma_spct' => $this->pma_spct,
            'pma_lo' => $this->pma_lo,
            'pma_ct' => $this->pma_ct,
            'pdien_giai' => $this->pdien_giai,
            'pma_nt' => $this->pma_nt,
        ];
    }

    public function render(): View
    {
        return view('catalog::gl.rpt.glrptbk01', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
