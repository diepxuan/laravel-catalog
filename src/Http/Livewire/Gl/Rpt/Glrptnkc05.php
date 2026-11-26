<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Gl\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsGLRptNKC05;
use Illuminate\Contracts\View\View;

/**
 * Tổng hợp NXT theo nhóm vật tư (GLRptNKC05) — 8 tham so, docs va wrapper khop.
 */
class Glrptnkc05 extends AbstractReport
{
    public ?string $pNgay_Ct1 = null;

    public ?string $pNgay_Ct2 = null;

    public ?string $pDs_Tk_pt = null;

    public ?string $pDs_Tk_hh = null;

    public ?string $pDs_Tk_Nvl = null;

    public ?string $pDs_Tk_khac = null;

    public ?string $pMa_Nt = null;

    protected function spClass(): string
    {
        return AsGLRptNKC05::class;
    }

    /**
     * @return array<string,mixed>
     */
    public function procedurePayload(): array
    {
        return [
            'pMa_Cty' => (string) \CatalogService::company()->id,
            'pNgay_Ct1' => $this->pNgay_Ct1,
            'pNgay_Ct2' => $this->pNgay_Ct2,
            'pDs_Tk_pt' => $this->pDs_Tk_pt,
            'pDs_Tk_hh' => $this->pDs_Tk_hh,
            'pDs_Tk_Nvl' => $this->pDs_Tk_Nvl,
            'pDs_Tk_khac' => $this->pDs_Tk_khac,
            'pMa_Nt' => $this->pMa_Nt,
        ];
    }

    public function render(): View
    {
        return view('catalog::gl.rpt.glrptnkc05', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
