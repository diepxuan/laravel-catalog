<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Fa\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsFARptBCPT10;
use Illuminate\Contracts\View\View;

/**
 * FARptBCPT10 (9 tham so).
 *
 * Hang B2: SP khong co trong procedures.md.
 */
class Farptbcpt10 extends AbstractReport
{
    public ?string $pNgay_dk = null;

    public ?string $pNgay_ck = null;

    public ?string $pMa_bpsd = null;

    public ?string $pMa_cc = null;

    public ?string $pMa_phi = null;

    public ?string $pMa_spct = null;

    public ?string $pTk_PB = null;

    public ?string $PTk_CP = null;

    protected function spClass(): string
    {
        return AsFARptBCPT10::class;
    }

    /**
     * @return array<string,mixed>
     */
    public function procedurePayload(): array
    {
        return [
            'pMa_Cty' => (string) \CatalogService::company()->id,
            'pNgay_dk' => $this->pNgay_dk,
            'pNgay_ck' => $this->pNgay_ck,
            'pMa_bpsd' => $this->pMa_bpsd,
            'pMa_cc' => $this->pMa_cc,
            'pMa_phi' => $this->pMa_phi,
            'pMa_spct' => $this->pMa_spct,
            'pTk_PB' => $this->pTk_PB,
            'PTk_CP' => $this->PTk_CP,
        ];
    }

    public function render(): View
    {
        return view('catalog::fa.rpt.farptbcpt10', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
