<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\So\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsARRptBCCN02;
use Illuminate\Contracts\View\View;

/**
 * ARRptBCCN02 (14 tham so) — Bang can doi phat sinh cong no.
 */
class Arrptbccn02 extends AbstractReport
{
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    public ?string $pTk = null;

    public ?string $pMa_kh = null;

    public ?string $pMa_plkh1 = null;

    public ?string $pMa_plkh2 = null;

    public ?string $pMa_plkh3 = null;

    public ?string $pMa_nt = null;

    public ?string $pGroup1 = null;

    public ?string $pGroup2 = null;

    public ?string $pGroup3 = null;



    protected function spClass(): string
    {
        return AsARRptBCCN02::class;
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
            'pTk' => $this->pTk,
            'pMa_kh' => $this->pMa_kh,
            'pMa_plkh1' => $this->pMa_plkh1,
            'pMa_plkh2' => $this->pMa_plkh2,
            'pMa_plkh3' => $this->pMa_plkh3,
            'pMa_nt' => $this->pMa_nt,
            'pGroup1' => $this->pGroup1,
            'pGroup2' => $this->pGroup2,
            'pGroup3' => $this->pGroup3,
        ];
    }

    public function render(): View
    {
        return view('catalog::so.rpt.arrptbccn02', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
