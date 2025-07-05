<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-07-05 19:34:16
 */

namespace Diepxuan\Catalog\Http\Livewire\Banhang;

use Diepxuan\Catalog\Models\SoPh3;
use Illuminate\View\View;
use Livewire\Component;

class Hoadonbanhang extends Component
{
    public $pMa_CT = 'SO3';
    public $pMa_Nt = 'VND';
    public $pMa_Kh;
    public $pTk;
    public $pMa_Kho;
    public $pMa_Vt;
    public $pMa_Bp;
    protected $soPhs;
    protected $soCts;

    public function mount(): void
    {
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
        // diepxuan/laravel-catalog/resources/views/banhang/hoadonbanhang.blade.php
        return view('catalog::banhang.hoadonbanhang', [
            'soPhs' => $this->soPhs,
            // 'soCts' => $this->soCts,
            // 'soPhCt' => $this->soPh,
        ])->layout('catalog::layouts.app');
    }

    protected function resultRender(): void
    {
        $this->soPhs = SoPh3::filterByNgayCt(\CatalogService::timerFrom(), \CatalogService::timerTo())
        // ->whereKhoXuat($request->get('khoxuat'))
        // ->orderBy('ngay_ct', 'asc')
        // ->orderBy('so_ct', 'asc')
        // ->orderBy('ma_kh', 'asc')
            ->get()
        ;

        // $soPh3 = SoPh3::getAsSoFilt3([
        //     'pMa_Cty'   => \CatalogService::company()->id,
        //     'pNgay_Ct1' => \CatalogService::timerFrom(),
        //     'pNgay_Ct2' => \CatalogService::timerTo(),
        //     'pMa_CT'    => $this->pMa_CT,
        //     'pMa_Kh'    => $this->pMa_Kh,
        //     'pMa_Nt'    => $this->pMa_Nt,
        //     'pMa_Bp'    => $this->pMa_Bp,
        // ]);
        // $this->soPhs = $soPh3['phs'];
        // $this->soCts = $soPh3['cts'];

        // Nếu có kết quả, phát ra event để yêu cầu chuyển tab
        if (!empty($this->soPhs)) {
            $this->dispatch('switch-tab', 'content');
        }

        // \Debugbar::info($this->soPhs);
    }
}
