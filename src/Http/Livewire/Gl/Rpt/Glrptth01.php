<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Gl\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsGLRptTH01;
use Illuminate\Contracts\View\View;

/**
 * GLRptTH01 (24 tham so) — Tong hop phat sinh nhom theo chi tieu.
 */
class Glrptth01 extends AbstractReport
{
    public ?string $pngay_ct1 = null;

    public ?string $pngay_ct2 = null;

    public ?string $pma_ct = null;

    public ?string $ptk = null;

    public ?string $ptk_du = null;

    public ?string $pma_plkh1 = null;

    public ?string $pma_plkh2 = null;

    public ?string $pma_plkh3 = null;

    public ?string $pma_kh = null;

    public ?string $pso_ct1 = null;

    public ?string $pso_ct2 = null;

    public ?string $pma_nt = null;

    public ?string $pma_hd = null;

    public ?string $pma_phi = null;

    public ?string $pdien_giai = null;

    public ?string $pma_bp = null;

    public ?string $pma_spct = null;

    public ?string $pma_lo = null;

    protected function spClass(): string
    {
        return AsGLRptTH01::class;
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
            'pma_ct' => $this->pma_ct,
            'ptk' => $this->ptk,
            'ptk_du' => $this->ptk_du,
            'pma_plkh1' => $this->pma_plkh1,
            'pma_plkh2' => $this->pma_plkh2,
            'pma_plkh3' => $this->pma_plkh3,
            'pma_kh' => $this->pma_kh,
            'pso_ct1' => $this->pso_ct1,
            'pso_ct2' => $this->pso_ct2,
            'pma_nt' => $this->pma_nt,
            'pma_hd' => $this->pma_hd,
            'pma_phi' => $this->pma_phi,
            'pdien_giai' => $this->pdien_giai,
            'pma_bp' => $this->pma_bp,
            'pma_spct' => $this->pma_spct,
            'pma_lo' => $this->pma_lo,
        ];
    }

    public function render(): View
    {
        return view('catalog::gl.rpt.glrptth01', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
