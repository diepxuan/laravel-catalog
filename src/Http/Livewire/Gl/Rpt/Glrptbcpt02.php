<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Gl\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsGLRptBCPT02;
use Illuminate\Contracts\View\View;

/**
 * Bảng cân đối phát sinh một TK theo ngày (BCPT02).
 *
 * Port từ GLRptBCPT02.dll (menu 02.60.05). SP `asGLRptBCPT02`.
 * Form gốc `frmGLRptBCPT02.LoadData()`:
 *   pMa_Cty, pTk, pNgay1, pNgay2, pMa_Nt
 *
 * Lưu ý: procedures.md ghi SP này KHÔNG có tham số ("Không có") — đối chiếu
 * form gốc + wrapper AsGLRptBCPT02 để lấy đúng 5 tham số thật.
 */
class Glrptbcpt02 extends AbstractReport
{
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    public string $pTk = '';

    public string $pMa_Nt = 'VND';

    protected function spClass(): string
    {
        return AsGLRptBCPT02::class;
    }

    /**
     * @return array<string,mixed>
     */
    public function procedurePayload(): array
    {
        return [
            'pMa_Cty' => (string) \CatalogService::company()->id,
            'pTk' => trim($this->pTk),
            'pNgay1' => $this->pNgay1,
            'pNgay2' => $this->pNgay2,
            'pMa_Nt' => trim($this->pMa_Nt),
        ];
    }

    public function render(): View
    {
        return view('catalog::gl.rpt.glrptbcpt02', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
