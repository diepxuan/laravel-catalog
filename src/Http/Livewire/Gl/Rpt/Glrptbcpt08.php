<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Gl\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsGLRptBCPT08;
use Illuminate\Contracts\View\View;

/**
 * asGLRptBCPT08 — 3 tham so.
 */
class Glrptbcpt08 extends AbstractReport
{
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    protected function spClass(): string
    {
        return AsGLRptBCPT08::class;
    }

    /**
     * @return array<string,mixed>
     */
    public function procedurePayload(): array
    {
        return [
            'pma_cty' => (string) \CatalogService::company()->id,
            'pNgay1' => $this->pNgay1,
            'pNgay2' => $this->pNgay2,
        ];
    }

    public function render(): View
    {
        return view('catalog::gl.rpt.glrptbcpt08', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
