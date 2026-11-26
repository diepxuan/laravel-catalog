<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Gl\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsGLRptBCTCCDTf03C;
use Illuminate\Contracts\View\View;

/**
 * GLRptBCTCCDTf03C (6 tham so).
 *
 * Hang B2: SP khong co trong procedures.md — tham so lay tu wrapper.
 */
class Glrptbctccdtf03c extends AbstractReport
{
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    public ?string $pTk = null;


    protected function spClass(): string
    {
        return AsGLRptBCTCCDTf03C::class;
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
            'pMa_cty' => (string) \CatalogService::company()->id,
        ];
    }

    public function render(): View
    {
        return view('catalog::gl.rpt.glrptbctccdtf03c', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
