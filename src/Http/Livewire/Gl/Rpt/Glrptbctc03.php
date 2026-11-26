<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Gl\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsGLRptBCTC03;
use Illuminate\Contracts\View\View;

/**
 * GLRptBCTC03 — 10 tham so.
 */
class Glrptbctc03 extends AbstractReport
{
    public ?string $pQd_cdkt = null;

    public ?string $pNgay_Ct1 = null;

    public ?string $pNgay_Ct2 = null;

    public ?string $pNgay_Ct01 = null;

    public ?string $pNgay_Ct02 = null;

    public ?string $pMau = null;

    public ?string $pMa_bp = null;

    public ?string $pMa_Nt = null;

    public ?string $pDest = null;

    protected function spClass(): string
    {
        return AsGLRptBCTC03::class;
    }

    /**
     * @return array<string,mixed>
     */
    public function procedurePayload(): array
    {
        return [
            'pMa_Cty' => (string) \CatalogService::company()->id,
            'pQd_cdkt' => $this->pQd_cdkt,
            'pNgay_Ct1' => $this->pNgay_Ct1,
            'pNgay_Ct2' => $this->pNgay_Ct2,
            'pNgay_Ct01' => $this->pNgay_Ct01,
            'pNgay_Ct02' => $this->pNgay_Ct02,
            'pMau' => $this->pMau,
            'pMa_bp' => $this->pMa_bp,
            'pMa_Nt' => $this->pMa_Nt,
            'pDest' => $this->pDest,
        ];
    }

    public function render(): View
    {
        return view('catalog::gl.rpt.glrptbctc03', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
