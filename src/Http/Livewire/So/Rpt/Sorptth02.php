<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\So\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsSORptTH02;
use Illuminate\Contracts\View\View;

/**
 * Bao cao tong hop ban hang 02 (SORptTH02) — 13 tham so.
 */
class Sorptth02 extends AbstractReport
{
    public ?string $pThang = null;

    public ?string $pNam = null;

    public ?string $pSo_ky = null;

    public ?string $pLoai = null;

    public ?string $pLoai_CT = null;

    public ?string $pMa_nt = null;

    public ?string $pIs_NT = null;

    public ?string $ploai = null;

    public ?string $par = null;

    public ?string $p2 = null;

    public ?string $pSoLoai = null;

    public ?string $pSoQuy = null;

    protected function spClass(): string
    {
        return AsSORptTH02::class;
    }

    /**
     * @return array<string,mixed>
     */
    public function procedurePayload(): array
    {
        return [
            'pma_cty' => (string) \CatalogService::company()->id,
            'pThang' => $this->pThang,
            'pNam' => $this->pNam,
            'pSo_ky' => $this->pSo_ky,
            'pLoai' => $this->pLoai,
            'pLoai_CT' => $this->pLoai_CT,
            'pMa_nt' => $this->pMa_nt,
            'pIs_NT' => $this->pIs_NT,
            'ploai' => $this->ploai,
            'par' => $this->par,
            'p2' => $this->p2,
            'pSoLoai' => $this->pSoLoai,
            'pSoQuy' => $this->pSoQuy,
        ];
    }

    public function render(): View
    {
        return view('catalog::so.rpt.sorptth02', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
