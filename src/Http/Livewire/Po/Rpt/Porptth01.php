<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\Po\Rpt;

use Diepxuan\Catalog\Http\Livewire\Rpt\AbstractReport;
use Diepxuan\Simba\StoredProcedures\AsPORptTH01;
use Illuminate\Contracts\View\View;

/**
 * PORptTH01 — 33 tham so theo form goc decompiled.
 */
class Porptth01 extends AbstractReport
{
    public ?string $pNgay1 = null;

    public ?string $pNgay2 = null;

    public ?string $pMa_ncc = null;


    public ?string $pPl_ncc1 = null;


    public ?string $pPl_ncc3 = null;

    public ?string $pMa_hd = null;

    public ?string $pMa_kho = null;


    public ?string $pMa_vt = null;

    public ?string $pMa_nh_vt = null;

    public ?string $pMa_Nt = null;


    public ?string $pMa_nhhd = null;

    public ?string $pMa_Plvt1 = null;

    public ?string $pMa_Plvt2 = null;

    public ?string $pMa_plvt3 = null;



    public ?string $pMA_HTTT = null;


    public ?string $pMa_spct = null;

    protected function spClass(): string
    {
        return AsPORptTH01::class;
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
            'pMa_ncc' => $this->pMa_ncc,
            'pPl_ncc1' => $this->pPl_ncc1,
            'pPl_ncc3' => $this->pPl_ncc3,
            'pMa_hd' => $this->pMa_hd,
            'pMa_kho' => $this->pMa_kho,
            'pMa_vt' => $this->pMa_vt,
            'pMa_nh_vt' => $this->pMa_nh_vt,
            'pMa_Nt' => $this->pMa_Nt,
            'pMa_nhhd' => $this->pMa_nhhd,
            'pMa_Plvt1' => $this->pMa_Plvt1,
            'pMa_Plvt2' => $this->pMa_Plvt2,
            'pMa_plvt3' => $this->pMa_plvt3,
            'pMA_HTTT' => $this->pMA_HTTT,
            'pMa_spct' => $this->pMa_spct,
        ];
    }

    public function render(): View
    {
        return view('catalog::po.rpt.porptth01', [
            'rows' => $this->rows,
            'columns' => $this->columns,
        ]);
    }
}
