<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Gl\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsGLRptBCTC01;
use Illuminate\Contracts\View\View;

/**
 * Bảng cân đối kế toán 01 (GLRptBCTC01) — 7 tham so, docs va wrapper khop.
 */
class Glrptbctc01 extends AbstractReport
{
    public ?string $Ngay_ct1 = null;

    public ?string $Ngay_ct2 = null;

    public ?string $pTk = null;

    public ?string $pBac_tk = null;

    public ?string $pTk_sc = null;

    public ?string $pMa_nt = null;

    protected function spClass(): string
    {
        return AsGLRptBCTC01::class;
    }

    /**
     * @return array<string,mixed>
     */
    public function procedurePayload(): array
    {
        return [
            'pMa_cty' => (string) \CatalogService::company()->id,
            'Ngay_ct1' => $this->Ngay_ct1,
            'Ngay_ct2' => $this->Ngay_ct2,
            'pTk' => $this->pTk,
            'pBac_tk' => $this->pBac_tk,
            'pTk_sc' => $this->pTk_sc,
            'pMa_nt' => $this->pMa_nt,
        ];
    }

    public function render(): View
    {
        return view('catalog::gl.rpt.glrptbctc01', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
