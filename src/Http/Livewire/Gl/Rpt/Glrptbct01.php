<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Gl\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsGLRptBCT01;
use Illuminate\Contracts\View\View;

/**
 * Bảng kê hoá đơn, chứng từ HHDV mua vào (GLRptBCT01).
 *
 * Port từ GLRptBCT01.dll (menu 02.40.02). SP `asGLRptBCT01` (8 tham số).
 * Form gốc `frmGLRptBCT01.LoadData()`:
 *   pMa_cty, pNgay1, pNgay2, pThue_suat, pTk_thue, pTk_du, pMa_bp, pMau_bc
 */
class Glrptbct01 extends AbstractReport
{
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    public ?int $pThue_suat = 10;

    public string $pTk_thue = '';

    public string $pTk_du = '';

    public string $pMa_bp = '';

    public int $pMau_bc = 0;

    protected function spClass(): string
    {
        return AsGLRptBCT01::class;
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
            'pTk_thue' => trim($this->pTk_thue),
            'pTk_du' => trim($this->pTk_du),
            'pMa_bp' => trim($this->pMa_bp),
            'pMau_bc' => $this->pMau_bc,
        ];
    }

    public function render(): View
    {
        return view('catalog::gl.rpt.glrptbct01', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
