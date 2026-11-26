<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Gl\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsGLRptBCTCCDTb03;
use Illuminate\Contracts\View\View;

/**
 * GLRptBCTCCDTb03 (5 tham so).
 *
 * Hang B2: SP khong co trong procedures.md — tham so lay tu wrapper.
 */
class Glrptbctccdtb03 extends AbstractReport
{
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    public ?string $pTk = null;

    public ?string $pBac = null;

    protected function spClass(): string
    {
        return AsGLRptBCTCCDTb03::class;
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
            'pBac' => $this->pBac,
        ];
    }

    public function render(): View
    {
        return view('catalog::gl.rpt.glrptbctccdtb03', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
