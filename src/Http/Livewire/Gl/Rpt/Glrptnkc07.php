<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Gl\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsGLRptNKC07;
use Illuminate\Contracts\View\View;

/**
 * Sổ chi tiết tài khoản theo nhóm TK (GLRptNKC07) — 6 tham so, docs va wrapper khop.
 */
class Glrptnkc07 extends AbstractReport
{
    public ?string $Ngay_ct1 = null;

    public ?string $Ngay_ct2 = null;

    public ?string $pTk = null;


    public ?string $pMa_nt = null;

    protected function spClass(): string
    {
        return AsGLRptNKC07::class;
    }

    /**
     * @return array<string,mixed>
     */
    public function procedurePayload(): array
    {
        return [
            'pMa_Cty' => (string) \CatalogService::company()->id,
            'Ngay_ct1' => $this->Ngay_ct1,
            'Ngay_ct2' => $this->Ngay_ct2,
            'pTk' => $this->pTk,
            'pMa_nt' => $this->pMa_nt,
        ];
    }

    public function render(): View
    {
        return view('catalog::gl.rpt.glrptnkc07', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
