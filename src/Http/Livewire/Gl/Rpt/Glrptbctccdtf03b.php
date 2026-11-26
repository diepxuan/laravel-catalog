<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Gl\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsGLRptBCTCCDTf03B;
use Illuminate\Contracts\View\View;

/**
 * GLRptBCTCCDTf03B (10 tham so).
 *
 * Hang B2: SP khong co trong procedures.md.
 */
class Glrptbctccdtf03b extends AbstractReport
{
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    public ?string $pTk_xl = null;

    public ?string $pTk_tb = null;

    public ?string $pTk_bt = null;

    public ?string $pTk_ql = null;

    public ?string $pTk_tv = null;

    public ?string $pTk_khac = null;

    public ?string $pMa_spct = null;

    protected function spClass(): string
    {
        return AsGLRptBCTCCDTf03B::class;
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
            'pTk_xl' => $this->pTk_xl,
            'pTk_tb' => $this->pTk_tb,
            'pTk_bt' => $this->pTk_bt,
            'pTk_ql' => $this->pTk_ql,
            'pTk_tv' => $this->pTk_tv,
            'pTk_khac' => $this->pTk_khac,
            'pMa_spct' => $this->pMa_spct,
        ];
    }

    public function render(): View
    {
        return view('catalog::gl.rpt.glrptbctccdtf03b', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
