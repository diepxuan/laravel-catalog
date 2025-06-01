<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-06-01 18:16:23
 */

namespace Diepxuan\Catalog\Http\Livewire\Banhang;

use Diepxuan\Catalog\Models\ArDmKh;
use Illuminate\View\View;
use Livewire\Component;

class Khachhang extends Component
{
    public $pTk_List   = '';
    public $pTkdu_List = '111';
    public $pMa_Nt     = 'VND';
    public $pMa_Bp;
    protected $arDmKhs;

    public function mount(): void
    {
        $this->arDmKhs = ArDmKh::query()
            ->where('isKh', true)
            ->orderBy('ma_kh')
            ->get()
        ;
    }

    public function updated($property): void
    {
        // \Debugbar::info($property);
    }

    /**
     * Render the component.
     *
     * @return View
     */
    public function render()
    {
        // diepxuan/laravel-catalog/resources/views/banhang/khachhang.blade.php
        return view('catalog::banhang.khachhang', [
            'arDmKhs' => $this->arDmKhs,
        ])->layout('catalog::layouts.app');
    }
}
