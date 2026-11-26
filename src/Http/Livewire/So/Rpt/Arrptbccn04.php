<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\So\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsARRptBCCN04;
use Illuminate\Contracts\View\View;

/**
 * Bảng cân đối công nợ 04 (ARRptBCCN04) — 15 tham so.
 */
class Arrptbccn04 extends AbstractReport
{
    public ?string $Ngay1 = null;

    public ?string $Ngay2 = null;

    public ?string $Tk = null;

    public ?string $pGroup = null;

    public ?string $pDetail = null;

    public ?string $ma_hd = null;

    public ?string $ma_kh = null;

    public ?string $ma_plkh1 = null;

    public ?string $ma_plkh2 = null;

    public ?string $ma_plkh3 = null;

    public ?string $pMa_BP = null;

    public ?string $pMa_NVKD = null;

    public ?string $pMa_SPCT = null;

    public ?string $ma_nt = null;

    protected function spClass(): string
    {
        return AsARRptBCCN04::class;
    }

    /**
     * @return array<string,mixed>
     */
    public function procedurePayload(): array
    {
        return [
            'ma_cty' => (string) \CatalogService::company()->id,
            'Ngay1' => $this->Ngay1,
            'Ngay2' => $this->Ngay2,
            'Tk' => $this->Tk,
            'pGroup' => $this->pGroup,
            'pDetail' => $this->pDetail,
            'ma_hd' => $this->ma_hd,
            'ma_kh' => $this->ma_kh,
            'ma_plkh1' => $this->ma_plkh1,
            'ma_plkh2' => $this->ma_plkh2,
            'ma_plkh3' => $this->ma_plkh3,
            'pMa_BP' => $this->pMa_BP,
            'pMa_NVKD' => $this->pMa_NVKD,
            'pMa_SPCT' => $this->pMa_SPCT,
            'ma_nt' => $this->ma_nt,
        ];
    }

    public function render(): View
    {
        return view('catalog::so.rpt.arrptbccn04', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
