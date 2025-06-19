<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-06-19 22:19:28
 */

namespace Diepxuan\Catalog\Http\Livewire\Cash\Baocao;

use Diepxuan\Catalog\Models\GlCt;
use Illuminate\View\View;
use Livewire\Component;

class Thu extends Component
{
    public $pTk_List = '111,112';
    public $pTkdu_List;
    public $pMa_Nt;
    public $pMa_Bp;
    protected $glCts;

    public function mount(): void
    {
        $this->pMa_Nt = \CatalogService::ma_Nt();
        $this->resultRender();
        \Debugbar::info(
            $this->glCts
        );
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
        // diepxuan/laravel-catalog/resources/views/cash/baocao/thu.blade.php
        return view('catalog::cash.baocao.thu', [
            'glCts' => $this->glCts,
        ])->layout('catalog::layouts.app');
    }

    protected function resultRender(): void
    {
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
