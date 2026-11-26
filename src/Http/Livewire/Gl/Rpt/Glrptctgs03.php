<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Gl\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsGLRptCTGS03;
use Illuminate\Contracts\View\View;

/**
 * Sổ cái chứng từ ghi sổ (GLRptCTGS03).
 *
 * 5 tham số theo form gốc frmGLRptCTGS03.LoadData():
 *   CompanyID, txtTk, txtNgay1, txtNgay2, txtMa_Nt
 */
class Glrptctgs03 extends AbstractReport
{
    public string $pTk = '';

    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    public string $pMa_Nt = 'VND';

    protected function spClass(): string
    {
        return AsGLRptCTGS03::class;
    }

    /**
     * @return array<string,mixed>
     */
    public function procedurePayload(): array
    {
        return [
            'pMa_cty' => (string) \CatalogService::company()->id,
            'pTk' => trim($this->pTk),
            'pNgay_ct1' => $this->pNgay1,
            'pNgay_ct2' => $this->pNgay2,
            'pMa_Nt' => trim($this->pMa_Nt),
        ];
    }

    public function render(): View
    {
        return view('catalog::gl.rpt.glrptctgs03', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
