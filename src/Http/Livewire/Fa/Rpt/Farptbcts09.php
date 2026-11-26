<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Fa\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsFARptBCTS09;
use Illuminate\Contracts\View\View;

/**
 * FARptBCTS09 (7 tham so).
 *
 * Hang B2: SP khong co trong procedures.md.
 */
class Farptbcts09 extends AbstractReport
{
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    public ?string $pMa_tscd = null;

    public ?string $pMa_cc = null;

    public ?string $pMa_bpsd = null;

    public ?string $pLoai = null;

    protected function spClass(): string
    {
        return AsFARptBCTS09::class;
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
            'pMa_tscd' => $this->pMa_tscd,
            'pMa_cc' => $this->pMa_cc,
            'pMa_bpsd' => $this->pMa_bpsd,
            'pLoai' => $this->pLoai,
        ];
    }

    public function render(): View
    {
        return view('catalog::fa.rpt.farptbcts09', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
