<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Gl\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsGLRptBCTCCDTf03A;
use Illuminate\Contracts\View\View;

/**
 * GLRptBCTCCDTf03A (10 tham so) — Chi tiet nguon von dau tu.
 */
class Glrptbctccdtf03a extends AbstractReport
{
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;






    public ?string $pTk_Khac = null;

    public ?string $pMa_SPCT = null;

    protected function spClass(): string
    {
        return AsGLRptBCTCCDTf03A::class;
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
            'pTk_Khac' => $this->pTk_Khac,
            'pMa_SPCT' => $this->pMa_SPCT,
        ];
    }

    public function render(): View
    {
        return view('catalog::gl.rpt.glrptbctccdtf03a', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
