<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-08-02 23:37:20
 */

namespace Diepxuan\Catalog\Http\Livewire\Component;

use Diepxuan\Catalog\Models\ArDmKh;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Modelable;
use Livewire\Component;

class InputKhachhang extends Component
{
    #[Modelable]
    public $pMa_Kh;
    protected $arDmKhs;

    public function boot(): void
    {
        $this->arDmKhs = ArDmKh::where('isKh', true)
            ->orderBy('ma_kh')
            ->get()
        ;
    }

    public function mount(): void {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): \Closure|string|View
    {
        return view('catalog::components.input-khachhang', [
            'arDmKhs' => $this->arDmKhs,
        ]);
    }
}
