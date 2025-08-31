<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-08-31 06:59:39
 */

namespace Diepxuan\Catalog\Http\Livewire\Cash\Nganhang;

use Diepxuan\Catalog\Models\GlCt;
use Illuminate\View\View;
use Livewire\Component;

class Baoco extends Component
{
    public $pTk_List   = '112';
    public $pTkdu_List = '';
    public $pMa_Nt     = 'VND';
    public $pMa_Bp;
    protected $glCts;

    public function mount(): void
    {
        // \Debugbar::info(\CatalogService::timerFrom());
        // \Debugbar::info(\CatalogService::timerTo());
        $this->pMa_Nt = \CatalogService::ma_Nt();
        $this->resultRender();
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
        // diepxuan/laravel-catalog/resources/views/cash/nganhang/baoco.blade.php
        return view('catalog::cash.nganhang.baoco', [
            'glCts' => $this->glCts,
        ])->layout('catalog::layouts.app');
    }

    protected function resultRender(): void
    {
        $this->glCts = GlCt::getNKThuChi([
            'ma_cty'    => \CatalogService::company()->id,
            'ngay_ct1'  => \CatalogService::timerFrom(),
            'ngay_ct2'  => \CatalogService::timerTo(),
            'tk_list'   => $this->pTk_List,
            'tkdu_list' => $this->pTkdu_List,
            'ma_bp'     => $this->pMa_Bp ?? '',
            'ma_nt'     => 'VND',
        ])->get();
    }
}
