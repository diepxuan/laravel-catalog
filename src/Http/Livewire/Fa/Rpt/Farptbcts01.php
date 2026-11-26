<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Fa\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsFARptBCTS01;
use Illuminate\Contracts\View\View;

/**
 * FARptBCTS01 (4 tham so).
 *
 * Hang B2: SP khong co trong procedures.md.
 */
class Farptbcts01 extends AbstractReport
{
    public ?string $pNgay = null;

    public ?string $pMa_ts = null;

    public ?string $pMa_ts_all = null;

    protected function spClass(): string
    {
        return AsFARptBCTS01::class;
    }

    /**
     * @return array<string,mixed>
     */
    public function procedurePayload(): array
    {
        return [
            'pMa_Cty' => (string) \CatalogService::company()->id,
            'pNgay' => $this->pNgay,
            'pMa_ts' => $this->pMa_ts,
            'pMa_ts_all' => $this->pMa_ts_all,
        ];
    }

    public function render(): View
    {
        return view('catalog::fa.rpt.farptbcts01', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
