<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\So\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsARRptBCCN05;
use Illuminate\Contracts\View\View;

/**
 * Bảng cân đối công nợ 05 (ARRptBCCN05) — 6 tham so.
 */
class Arrptbccn05063026 extends AbstractReport
{
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    public ?string $pTk = null;

    public ?string $pMa_kh = null;

    public ?string $pMa_nt = null;

    protected function spClass(): string
    {
        return AsARRptBCCN05::class;
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
            'pMa_nt' => $this->pMa_nt,
        ];
    }

    public function render(): View
    {
        return view('catalog::so.rpt.arrptbccn05063026', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
