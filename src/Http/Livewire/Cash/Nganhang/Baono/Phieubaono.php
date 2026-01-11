<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-01-12 00:36:13
 */

namespace Diepxuan\Catalog\Http\Livewire\Cash\Nganhang\Baono;

use Diepxuan\Catalog\Models\ArDmKh;
use Diepxuan\Simba\StoredProcedures\AsGetSoCt;
use Illuminate\View\View;
use Livewire\Component;

class Phieubaono extends Component
{
    public $pMa_Kh;
    public $pKh;
    public $pDien_Giai;
    public $pTk_Co = 11_217;
    public $pNgay_Ct;
    public $pSo_Ct;
    public $pNgay_Lap;
    public $pCts;
    public $pSoDu;

    public function mount(): void
    {
        // \Debugbar::info(\CatalogService::timerFrom());
        // \Debugbar::info(\CatalogService::timerTo());
        // \Debugbar::info(\CatalogService::timer());
        $this->pNgay_Ct  = now()->toDateString();
        $this->pNgay_Lap = now()->toDateString();
        $this->pSo_Ct    = AsGetSoCt::call([
            'pMa_ct'   => 'CA4',
            'pNgay_Ct' => $this->pNgay_Ct,
        ]);
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

    public function updateKhachHang(): void
    {
        $this->pKh   = ArDmKh::where('ma_kh', $this->pMa_Kh)->first();
        $this->pSoDu = $this->pKh->getSoduKh([
            'pTk'   => $this->pTk_Co,
            'pNgay' => $this->pNgay_Ct,
        ]);
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
        return view('catalog::cash.nganhang.baono.phieubaono', [
            'arDmKhs' => ArDmKh::all(),
        ]);
    }
}
