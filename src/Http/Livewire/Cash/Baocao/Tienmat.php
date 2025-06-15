<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-06-09 16:41:24
 */

namespace Diepxuan\Catalog\Http\Livewire\Cash\Baocao;

use Diepxuan\Catalog\Models\GlCt;
use Illuminate\View\View;
use Livewire\Component;

class Tienmat extends Component
{
    public $pTk    = '111';
    public $pMa_Nt = 'VND';
    public $duCk;
    public $glCtDk;
    protected $glCts;

    public function mount(): void
    {
        // \Debugbar::info(\CatalogService::timerFrom());
        // \Debugbar::info(\CatalogService::timerTo());
        // \Debugbar::info(\CatalogService::company()->siSetup->ngay_dntc);
        // \Debugbar::info(\CatalogFunctions::afNamTC([
        //     'ma_cty' => \CatalogService::company()->ma_cty,
        //     'ngay'   => \CatalogService::timerFrom(),
        // ]));
        // $glCtDk = \CatalogFunctions::afDuDauTk([
        //     'pMaCty' => \CatalogService::company()->ma_cty,
        //     'pNgay'  => \CatalogService::timerFrom(),
        // ]);
        // $this->glCtDk = [
        //     'du_no'    => $glCtDk->sum(static fn ($i) => (float) $i->du_no - (float) $i->du_co),
        //     'du_co'    => $glCtDk->sum(static fn ($i) => 0),
        //     'du_no_nt' => $glCtDk->sum(static fn ($i) => (float) $i->du_no_nt - (float) $i->du_co_nt),
        //     'du_co_nt' => $glCtDk->sum(static fn ($i) => 0),
        // ];
        // \Debugbar::info(
        //     $glCtDk
        // );
    }

    public function updated($property): void
    {
        // \Debugbar::info($property);
    }

    public function submit(): void
    {
        $this->glCtDk = \CatalogFunctions::afDuDauTk([
            'pMaCty' => \CatalogService::company()->ma_cty,
            'pNgay'  => \CatalogService::timerFrom(),
        ]);
        $this->duCk  = $this->glCtDk->du_no - $this->glCtDk->du_co;
        $this->glCts = GlCt::getCARptTMNH([
            'ma_Cty'   => \CatalogService::company()->id,
            'ngay_Ct1' => \CatalogService::timerFrom(),
            'ngay_Ct2' => \CatalogService::timerTo(),
            'tk'       => $this->pTk,
            'ma_Nt'    => \CatalogService::ma_Nt(),
        ])
            ->get()
            ->map(function ($glCt) {
                $this->duCk += (float) $glCt->ps_no - (float) $glCt->ps_co;
                // \Debugbar::info($this->duCk);
                $glCt->ton = (float) $this->duCk;

                return $glCt;
            })
        ;
    }

    /**
     * Render the component.
     *
     * @return View
     */
    public function render()
    {
        // diepxuan/laravel-catalog/resources/views/cash/baocao/tienmat.blade.php
        return view('catalog::cash.baocao.tienmat', [
            'glCts' => $this->glCts,
        ])->layout('catalog::layouts.app');
    }
}
