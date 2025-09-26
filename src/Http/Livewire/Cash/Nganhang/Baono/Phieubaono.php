<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-09-18 22:53:23
 */

namespace Diepxuan\Catalog\Http\Livewire\Cash\Nganhang\Baono;

use Illuminate\View\View;
use Livewire\Component;

class Phieubaono extends Component
{
    public $pMa_Kh;
    public $pDien_Giai;
    public $pTk_Co;
    public $pNgay_Ct;
    public $pSo_Ct;
    public $pNgay_Lap;

    public function mount(): void
    {
        // \Debugbar::info(\CatalogService::timerFrom());
        // \Debugbar::info(\CatalogService::timerTo());
        // \Debugbar::info(\CatalogService::timer());
        $this->pNgay_Ct  = now()->toDateString();
        $this->pNgay_Lap = now()->toDateString();
        // $this->resultRender();
    }

    public function updated($property): void
    {
        // \Debugbar::info($property);
    }

    public function submit(): void
    {
        // $this->resultRender();
    }

    /**
     * Render the component.
     *
     * @return View
     */
    public function render()
    {
        // diepxuan/laravel-catalog/resources/views/cash/nganhang/baono/phieubaono.blade.php
        return view('catalog::cash.nganhang.baono.phieubaono');
    }
}
