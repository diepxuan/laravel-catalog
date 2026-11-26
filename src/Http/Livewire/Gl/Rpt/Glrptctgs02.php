<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Gl\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsGLRptCTGS02;
use Illuminate\Contracts\View\View;

/**
 * Chứng từ ghi sổ (GLRptCTGS02) — 6 tham so.
 *
 * Wrapper dung (6 tham so). LUU Y: procedures.md chi ghi 5 (thieu pCtu2),
 * nhung form goc frmGLRptCTGS02.LoadData() co txtDen_so -> can pCtu2.
 */
class Glrptctgs02 extends AbstractReport
{
    public ?string $pNgay_ct1 = null;

    public ?string $pNgay_ct2 = null;

    public ?string $pCtu1 = null;



    protected function spClass(): string
    {
        return AsGLRptCTGS02::class;
    }

    /**
     * @return array<string,mixed>
     */
    public function procedurePayload(): array
    {
        return [
            'pMa_cty' => (string) \CatalogService::company()->id,
            'pNgay_ct1' => $this->pNgay_ct1,
            'pNgay_ct2' => $this->pNgay_ct2,
            'pCtu1' => $this->pCtu1,
        ];
    }

    public function render(): View
    {
        return view('catalog::gl.rpt.glrptctgs02', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
