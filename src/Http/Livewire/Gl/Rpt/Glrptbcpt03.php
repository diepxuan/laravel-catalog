<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Gl\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsGLRptBCPT03;
use Illuminate\Contracts\View\View;

/**
 * GLRptBCPT03 (6 tham so) — Bang can doi phat sinh.
 */
class Glrptbcpt03 extends AbstractReport
{
    public ?string $pLoai = null;

    public ?string $pThang = null;

    public ?string $pNam = null;

    public ?string $pSo_ky = null;

    public ?string $pMau = null;

    protected function spClass(): string
    {
        return AsGLRptBCPT03::class;
    }

    /**
     * @return array<string,mixed>
     */
    public function procedurePayload(): array
    {
        return [
            'pMa_Cty' => (string) \CatalogService::company()->id,
            'pLoai' => $this->pLoai,
            'pThang' => $this->pThang,
            'pNam' => $this->pNam,
            'pSo_ky' => $this->pSo_ky,
            'pMau' => $this->pMau,
        ];
    }

    public function render(): View
    {
        return view('catalog::gl.rpt.glrptbcpt03', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
