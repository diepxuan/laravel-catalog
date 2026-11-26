<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Gl\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsGLRptBCT02;
use Illuminate\Contracts\View\View;

/**
 * Bảng kê hoá đơn, chứng từ HHDV bán ra (GLRptBCT02).
 *
 * Port từ GLRptBCT02.dll (menu 02.40.05). SP `asGLRptBCT02` (8 tham số).
 * Form gốc `frmGLRptBCT02.LoadData()`:
 *   pMa_cty, pNgay1, pNgay2, pThue_suat, pTk, pTk_du
 * (BCT02 chi 6 tham so — KHAC BCT01 8 tham so; da doi chieu wrapper + docs)
 */
class Glrptbct02 extends AbstractReport
{
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    public ?int $pThue_suat = 10;

    public string $pTk = '';

    public string $pTk_du = '';

    protected function spClass(): string
    {
        return AsGLRptBCT02::class;
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
            'pThue_suat' => $this->pThue_suat,
            'pTk' => trim($this->pTk),
            'pTk_du' => trim($this->pTk_du),
        ];
    }

    public function render(): View
    {
        return view('catalog::gl.rpt.glrptbct02', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
