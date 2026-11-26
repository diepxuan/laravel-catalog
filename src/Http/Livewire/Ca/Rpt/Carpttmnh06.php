<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Ca\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsCARptTMNH06;
use Illuminate\Contracts\View\View;

/**
 * Sổ quỹ tiền mặt ngoại tệ (CARptTMNH06).
 */
class Carpttmnh06 extends AbstractReport
{
    public string $pTk = '';

    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    public string $pMa_Ku = '';

    public string $pMa_Nt = 'VND';

    protected function spClass(): string
    {
        return AsCARptTMNH06::class;
    }

    /**
     * @return array<string,mixed>
     */
    public function procedurePayload(): array
    {
        return [
            'pMa_cty' => (string) \CatalogService::company()->id,
            'pNgay_ct1' => $this->pNgay1,
            'pNgay_ct2' => $this->pNgay2,
            'pTk' => trim($this->pTk),
            'pMa_Ku' => trim($this->pMa_Ku),
            'pMa_Nt' => trim($this->pMa_Nt),
        ];
    }

    public function render(): View
    {
        return view('catalog::ca.rpt.carpttmnh06', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
