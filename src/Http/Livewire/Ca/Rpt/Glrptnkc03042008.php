<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Ca\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsGLRptNKC03;
use Illuminate\Contracts\View\View;

/**
 * Sổ nhật ký thu tiền (GLRptNKC03, menu 04.20.08).
 *
 * 7 tham số theo wrapper. Route có suffix 042008 (DLL xuất hiện ở 4 menu, 2 module).
 */
class Glrptnkc03042008 extends AbstractReport
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
        return view('catalog::ca.rpt.glrptnkc03042008', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
