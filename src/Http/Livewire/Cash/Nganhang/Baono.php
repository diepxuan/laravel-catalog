<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-09-14 20:07:23
 */

namespace Diepxuan\Catalog\Http\Livewire\Cash\Nganhang;

use Diepxuan\Catalog\Models\GlCt;
use Illuminate\View\View;
use Livewire\Component;

class Baono extends Component
{
    public $pTk_List   = '';
    public $pTkdu_List = '112';
    public $pMa_Nt     = 'VND';
    public $pMa_Bp;
    protected $glCts;

    public function mount(): void
    {
        // \Debugbar::info(\CatalogService::timerFrom());
        // \Debugbar::info(\CatalogService::timerTo());
        $this->pMa_Nt = \CatalogService::ma_Nt();
        // $this->resultRender();
    }

    public function updated($property): void
    {
        // \Debugbar::info($property);
    }

    public function submit(): void
    {
        $this->resultRender();
    }

    /**
     * Render the component.
     *
     * @return View
     */
    public function render()
    {
        // diepxuan/laravel-catalog/resources/views/cash/nganhang/baono.blade.php
        return view('catalog::cash.nganhang.baono', [
            'glCts' => $this->glCts,
        ])->layout('catalog::layouts.app');
    }

    protected function resultRender(): void
    {
        // $this->glCts = GlCt::getNKThuChi([
        //     'ma_cty'    => \CatalogService::company()->id,
        //     'ngay_ct1'  => \CatalogService::timerFrom(),
        //     'ngay_ct2'  => \CatalogService::timerTo(),
        //     'tk_list'   => $this->pTk_List,
        //     'tkdu_list' => $this->pTkdu_List,
        //     'ma_bp'     => $this->pMa_Bp ?? '',
        //     'ma_nt'     => 'VND',
        // ])->get();

        $this->glCts = GlCt::getGLRptNKC03([
            'pMa_Cty'    => \CatalogService::company()->id,
            'pNgay_Ct1'  => \CatalogService::timerFrom(),
            'pNgay_Ct2'  => \CatalogService::timerTo(),
            'pTk_List'   => $this->pTk_List,
            'pTkdu_List' => $this->pTkdu_List,
            'pMa_Nt'     => $this->pMa_Nt,
            'pMa_Bp'     => $this->pMa_Bp,
        ]);
    }
}
