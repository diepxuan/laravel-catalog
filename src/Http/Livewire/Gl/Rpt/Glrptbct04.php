<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Gl\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsGLRptBCT04;
use Illuminate\Contracts\View\View;

/**
 * Tờ khai thuế thu nhập doanh nghiệp tạm tính (GLRptBCT04).
 *
 * 4 tham số: pMa_Cty, pNgay1, pNgay2, pMau — docs, wrapper khớp nhau.
 */
class Glrptbct04 extends AbstractReport
{
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    public string $pMau = '01';

    protected function spClass(): string
    {
        return AsGLRptBCT04::class;
    }

    /**
     * @return array<string,mixed>
     */
    public function procedurePayload(): array
    {
        return [
            'pMa_Cty' => (string) \CatalogService::company()->id,
            'pNgay1' => $this->pNgay1,
            'pNgay2' => $this->pNgay2,
            'pMau' => trim($this->pMau),
        ];
    }

    public function render(): View
    {
        return view('catalog::gl.rpt.glrptbct04', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
