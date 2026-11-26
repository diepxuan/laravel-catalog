<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Fa\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsFARptBCTS07;
use Illuminate\Contracts\View\View;

/**
 * FARptBCTS07 (12 tham so).
 *
 * Hang B2: SP khong co trong procedures.md.
 */
class Farptbcts07 extends AbstractReport
{
    public ?string $pNgay_ck = null;

    public ?string $pMa_ts = null;

    public ?string $pMa_Nhts1 = null;

    public ?string $pMa_Nhts2 = null;

    public ?string $pMa_Nhts3 = null;

    public ?string $pMa_bpsd = null;

    public ?string $pMa_phi = null;

    public ?string $pMa_spct = null;

    public ?string $pGroup1 = null;

    public ?string $pGroup2 = null;

    public ?string $pGroup3 = null;

    protected function spClass(): string
    {
        return AsFARptBCTS07::class;
    }

    /**
     * @return array<string,mixed>
     */
    public function procedurePayload(): array
    {
        return [
            'pMa_Cty' => (string) \CatalogService::company()->id,
            'pNgay_ck' => $this->pNgay_ck,
            'pMa_ts' => $this->pMa_ts,
            'pMa_Nhts1' => $this->pMa_Nhts1,
            'pMa_Nhts2' => $this->pMa_Nhts2,
            'pMa_Nhts3' => $this->pMa_Nhts3,
            'pMa_bpsd' => $this->pMa_bpsd,
            'pMa_phi' => $this->pMa_phi,
            'pMa_spct' => $this->pMa_spct,
            'pGroup1' => $this->pGroup1,
            'pGroup2' => $this->pGroup2,
            'pGroup3' => $this->pGroup3,
        ];
    }

    public function render(): View
    {
        return view('catalog::fa.rpt.farptbcts07', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
