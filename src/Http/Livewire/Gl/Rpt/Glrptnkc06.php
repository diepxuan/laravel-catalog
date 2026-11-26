<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Gl\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsGLRptNKC06;
use Illuminate\Contracts\View\View;

/**
 * So chi tiet tai khoan (GLRptNKC06) — 5 tham so.
 *
 * LUU Y: SP nay RAT CHAM (~4m47s) — can tang max_execution_time.
 */
class Glrptnkc06 extends AbstractReport
{
    public ?string $pNgay_ct1 = null;

    public ?string $pNgay_ct2 = null;

    public ?string $pTk = null;

    public ?string $pMa_Nt = null;

    protected function spClass(): string
    {
        return AsGLRptNKC06::class;
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
            'pTk' => $this->pTk,
            'pMa_Nt' => $this->pMa_Nt,
        ];
    }

    public function render(): View
    {
        return view('catalog::gl.rpt.glrptnkc06', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
