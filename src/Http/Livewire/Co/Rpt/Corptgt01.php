<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Co\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsCORptGt01;
use Illuminate\Contracts\View\View;

/**
 * CORptGt01 (6 tham so) — Bao cao gia thanh.
 */
class Corptgt01 extends AbstractReport
{
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    public ?string $pMa_SPCT = null;

    public ?string $pCt_vt = null;

    protected function spClass(): string
    {
        return AsCORptGt01::class;
    }

    /**
     * @return array<string,mixed>
     */
    public function procedurePayload(): array
    {
        return [
            'pMa_cty' => (string) \CatalogService::company()->id,
            'pNgay1' => $this->pNgay1,
            'pNgay2' => $this->pNgay2,
            'pMa_SPCT' => $this->pMa_SPCT,
            'pCt_vt' => $this->pCt_vt,
        ];
    }

    public function render(): View
    {
        return view('catalog::co.rpt.corptgt01', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
