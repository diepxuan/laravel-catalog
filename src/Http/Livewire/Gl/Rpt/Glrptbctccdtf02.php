<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Gl\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsGLRptBCTCCDTf02;
use Illuminate\Contracts\View\View;

/**
 * Chi tiết nguồn vốn đầu tư (BCTCCDT F02).
 *
 * Port từ GLRptBCTCCDTF02.dll (menu 02.55.05). SP `asGLRptBCTCCDTF02` (5 tham số).
 * Form gốc `frmGLRptBCTCCDTF02.LoadData()`:
 *   pMa_cty, Ngay_ct1, Ngay_ct2, pTk, pMa_spct
 */
class Glrptbctccdtf02 extends AbstractReport
{
    public ?string $Ngay_ct1 = null;

    public ?string $Ngay_ct2 = null;

    public string $pTk = '';

    public string $pMa_spct = '';

    protected function spClass(): string
    {
        return AsGLRptBCTCCDTf02::class;
    }

    /**
     * @return array<string,mixed>
     */
    public function procedurePayload(): array
    {
        return [
            'pMa_cty' => (string) \CatalogService::company()->id,
            'Ngay_ct1' => $this->Ngay_ct1,
            'Ngay_ct2' => $this->Ngay_ct2,
            'pTk' => trim($this->pTk),
            'pMa_spct' => trim($this->pMa_spct),
        ];
    }

    public function render(): View
    {
        return view('catalog::gl.rpt.glrptbctccdtf02', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
