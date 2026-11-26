<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Fa\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsFARptBCTS05;
use Illuminate\Contracts\View\View;

/**
 * FARptBCTS05 (6 tham so).
 *
 * Hang B2: SP khong co trong procedures.md.
 */
class Farptbcts05 extends AbstractReport
{
    public ?string $pNgay_Dk = null;

    public ?string $pNgay_Ck = null;

    public ?string $pMa_bpsd = null;

    public ?string $pMa_phi = null;

    public ?string $pMa_spct = null;

    protected function spClass(): string
    {
        return AsFARptBCTS05::class;
    }

    /**
     * @return array<string,mixed>
     */
    public function procedurePayload(): array
    {
        return [
            'pMa_Cty' => (string) \CatalogService::company()->id,
            'pNgay_Dk' => $this->pNgay_Dk,
            'pNgay_Ck' => $this->pNgay_Ck,
            'pMa_bpsd' => $this->pMa_bpsd,
            'pMa_phi' => $this->pMa_phi,
            'pMa_spct' => $this->pMa_spct,
        ];
    }

    public function render(): View
    {
        return view('catalog::fa.rpt.farptbcts05', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
