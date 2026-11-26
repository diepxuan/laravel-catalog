<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Po\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsPORptTH02;
use Illuminate\Contracts\View\View;

/**
 * Báo cáo tổng hợp mua hàng (PORptTH02) — 12 tham so.
 */
class Porptth02 extends AbstractReport
{
    public ?string $pLoai = null;

    public ?string $pMa_ct = null;

    public ?string $pThang = null;

    public ?string $pNam = null;

    public ?string $pSo_ky = null;

    public ?string $pMa_nt = null;

    public ?string $pIs_nt = null;





    protected function spClass(): string
    {
        return AsPORptTH02::class;
    }

    /**
     * @return array<string,mixed>
     */
    public function procedurePayload(): array
    {
        return [
            'pma_cty' => (string) \CatalogService::company()->id,
            'pLoai' => $this->pLoai,
            'pMa_ct' => $this->pMa_ct,
            'pThang' => $this->pThang,
            'pNam' => $this->pNam,
            'pSo_ky' => $this->pSo_ky,
            'pMa_nt' => $this->pMa_nt,
            'pIs_nt' => $this->pIs_nt,
        ];
    }

    public function render(): View
    {
        return view('catalog::po.rpt.porptth02', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
