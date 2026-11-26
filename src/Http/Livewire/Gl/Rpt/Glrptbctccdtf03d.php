<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Gl\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsGLRptBCTCCDTf03D;
use Illuminate\Contracts\View\View;

/**
 * GLRptBCTCCDTf03D (4 tham so).
 *
 * Hang B2: SP khong co trong procedures.md.
 */
class Glrptbctccdtf03d extends AbstractReport
{
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    public ?string $pTk = null;

    protected function spClass(): string
    {
        return AsGLRptBCTCCDTf03D::class;
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
            'pTk' => $this->pTk,
        ];
    }

    public function render(): View
    {
        return view('catalog::gl.rpt.glrptbctccdtf03d', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
