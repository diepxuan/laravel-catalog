<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Gl\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsGLRptCTGS01;
use Illuminate\Contracts\View\View;

/**
 * Sổ đăng ký chứng từ ghi sổ (GLRptCTGS01).
 *
 * 3 tham số: pMa_cty, pNgay_ct1, pNgay_ct2 — docs và wrapper khớp.
 */
class Glrptctgs01 extends AbstractReport
{
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    protected function spClass(): string
    {
        return AsGLRptCTGS01::class;
    }

    /**
     * @return array<string,mixed>
     */
    public function procedurePayload(): array
    {
        return [
            'pMa_cty' => (string) \CatalogService::company()->id,
            'pNgay_ct1' => $this->pNgay1,
            'pNgay_ct2' => $this->pNgay2,
        ];
    }

    public function render(): View
    {
        return view('catalog::gl.rpt.glrptctgs01', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
