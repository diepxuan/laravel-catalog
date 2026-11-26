<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Gl\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsGLRptBCTC02;
use Illuminate\Contracts\View\View;

/**
 * Bảng cân đối kế toán 02 (GLRptBCTC02) — 6 tham so, docs va wrapper khop.
 */
class Glrptbctc02 extends AbstractReport
{
    public ?string $pQd_cdkt = null;

    public ?string $pNgay = null;

    public ?string $pChiTieuCoSoLieu = null;

    public ?string $pMau = null;

    public ?string $pDest = null;

    protected function spClass(): string
    {
        return AsGLRptBCTC02::class;
    }

    /**
     * @return array<string,mixed>
     */
    public function procedurePayload(): array
    {
        return [
            'pma_cty' => (string) \CatalogService::company()->id,
            'pQd_cdkt' => $this->pQd_cdkt,
            'pNgay' => $this->pNgay,
            'pChiTieuCoSoLieu' => $this->pChiTieuCoSoLieu,
            'pMau' => $this->pMau,
            'pDest' => $this->pDest,
        ];
    }

    public function render(): View
    {
        return view('catalog::gl.rpt.glrptbctc02', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
