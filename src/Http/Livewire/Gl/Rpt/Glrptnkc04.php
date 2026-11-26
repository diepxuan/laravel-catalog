<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Gl\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsGLRptNKC04;
use Illuminate\Contracts\View\View;

/**
 * Tổng hợp NXT theo khoản mục (GLRptNKC04) — 8 tham so, docs va wrapper khop.
 */
class Glrptnkc04 extends AbstractReport
{
    public ?string $pNgay_Ct1 = null;

    public ?string $pNgay_ct2 = null;

    public ?string $pDs_Tk_Pt = null;

    public ?string $pDs_Tk_Dt_Hh = null;

    public ?string $pDs_Tk_Dt_Tp = null;

    public ?string $pDs_Tk_Dt_Dv = null;

    public ?string $pMa_Nt = null;

    protected function spClass(): string
    {
        return AsGLRptNKC04::class;
    }

    /**
     * @return array<string,mixed>
     */
    public function procedurePayload(): array
    {
        return [
            'pMa_Cty' => (string) \CatalogService::company()->id,
            'pNgay_Ct1' => $this->pNgay_Ct1,
            'pNgay_ct2' => $this->pNgay_ct2,
            'pDs_Tk_Pt' => $this->pDs_Tk_Pt,
            'pDs_Tk_Dt_Hh' => $this->pDs_Tk_Dt_Hh,
            'pDs_Tk_Dt_Tp' => $this->pDs_Tk_Dt_Tp,
            'pDs_Tk_Dt_Dv' => $this->pDs_Tk_Dt_Dv,
            'pMa_Nt' => $this->pMa_Nt,
        ];
    }

    public function render(): View
    {
        return view('catalog::gl.rpt.glrptnkc04', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
