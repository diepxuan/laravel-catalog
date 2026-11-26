<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Gl\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsGLRptNKC01All;
use Illuminate\Contracts\View\View;

/**
 * asGLRptNKC01All — 7 tham so.
 */
class Glrptnkc01all extends AbstractReport
{
    public ?string $Ngay_ct1 = null;

    public ?string $Ngay_ct2 = null;

    public ?string $pTk = null;

    public ?string $pBac_tk = null;

    public ?string $pTk_sc = null;

    public ?string $pMa_nt = null;

    protected function spClass(): string
    {
        return AsGLRptNKC01All::class;
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
        return view('catalog::gl.rpt.glrptnkc01all', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
