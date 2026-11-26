<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Gl\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsGLRptBCTC04;
use Illuminate\Contracts\View\View;

/**
 * Bảng cân đối kế toán 04 (GLRptBCTC04) — 8 tham so, docs va wrapper khop.
 */
class Glrptbctc04 extends AbstractReport
{
    public ?string $pQd_cdkt = null;

    public ?string $pNgay_Ct1 = null;

    public ?string $pNgay_Ct2 = null;

    public ?string $pNgay_Ct01 = null;

    public ?string $pNgay_Ct02 = null;

    public ?string $pMau = null;

    public ?string $pMa_nt = null;

    protected function spClass(): string
    {
        return AsGLRptBCTC04::class;
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
            'pMa_nt' => $this->pMa_nt,
        ];
    }

    public function render(): View
    {
        return view('catalog::gl.rpt.glrptbctc04', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
