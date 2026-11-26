<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Gl\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsGLRptNKC03;
use Illuminate\Contracts\View\View;

/**
 * Sổ nhật ký chi tiền (GLRptNKC03, menu 02.25.11).
 *
 * 7 tham số theo wrapper. Route có suffix 022511.
 */
class Glrptnkc03022511 extends AbstractReport
{
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    public string $pTk_List = '';

    public string $pTkdu_List = '';

    public string $pMa_Bp = '';

    public string $pMa_Nt = 'VND';

    protected function spClass(): string
    {
        return AsGLRptNKC03::class;
    }

    /**
     * @return array<string,mixed>
     */
    public function procedurePayload(): array
    {
        return [
            'pMa_Cty' => (string) \CatalogService::company()->id,
            'pNgay_Ct1' => $this->pNgay1,
            'pNgay_Ct2' => $this->pNgay2,
            'pTk_List' => trim($this->pTk_List),
            'pTkdu_List' => trim($this->pTkdu_List),
            'pMa_Bp' => trim($this->pMa_Bp),
            'pMa_Nt' => trim($this->pMa_Nt),
        ];
    }

    public function render(): View
    {
        return view('catalog::gl.rpt.glrptnkc03022511', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
