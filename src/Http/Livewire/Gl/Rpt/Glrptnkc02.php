<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Gl\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsGLRptNKC02;
use Illuminate\Contracts\View\View;

/**
 * Sổ nhật ký chung (GLRptNKC02).
 *
 * 4 tham số: pMa_cty, pNgay_ct1, pNgay_ct2, pMa_Nt — docs, wrapper và form gốc khớp nhau.
 */
class Glrptnkc02 extends AbstractReport
{
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    public string $pMa_Nt = 'VND';

    protected function spClass(): string
    {
        return AsGLRptNKC02::class;
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
            'pMa_Nt' => trim($this->pMa_Nt),
        ];
    }

    public function render(): View
    {
        return view('catalog::gl.rpt.glrptnkc02', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
