<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-01-11 21:25:10
 */

namespace Diepxuan\Catalog\Http\Livewire\Banhang;

use Diepxuan\Catalog\Models\ArDmKh;
use Diepxuan\Support\Collection;
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
        $this->arDmKhs = ArDmKh::where('isKh', true)
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
        $this->arDmKhs = $this->arDmKhs instanceof Collection
            ? $this->arDmKhs
            : Collection::make($this->arDmKhs->all());

        // diepxuan/laravel-catalog/resources/views/banhang/khachhang.blade.php
        return view('catalog::banhang.khachhang', [
            'arDmKhs' => $this->arDmKhs,
        ])->layout('catalog::layouts.app');
    }
}
