<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Gl\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsGLRptBCT06;
use Illuminate\Contracts\View\View;

/**
 * Phu luc ket qua hoat dong san xuat kinh doanh.
 *
 * SP `asGLRptBCT06` — 4 tham so, doi chieu simba-docs + wrapper.
 */
class Glrptbct06 extends AbstractReport
{
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    public int $pMau = 0;
    /**
     * @return array<string,mixed>
     */
    public function procedurePayload(): array
    {
        return [
            'pMa_Cty' => (string) \CatalogService::company()->id,
            'pNgay1' => $this->pNgay1,
            'pNgay2' => $this->pNgay2,
            'pMau' => $this->pMau,
        ];
    }

    protected function spClass(): string
    {
        return AsGLRptBCT06::class;
    }

    public function render(): View
    {
        return view('catalog::gl.rpt.glrptbct06', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
