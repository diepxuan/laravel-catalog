<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Co\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsCORptGt02;
use Illuminate\Contracts\View\View;

/**
 * CORptGt02 (5 tham so) — Bao cao gia thanh.
 */
class Corptgt02 extends AbstractReport
{
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    public ?string $pMa_SPCT = null;

    protected function spClass(): string
    {
        return AsCORptGt02::class;
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
        ];
    }

    public function render(): View
    {
        return view('catalog::co.rpt.corptgt02', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
