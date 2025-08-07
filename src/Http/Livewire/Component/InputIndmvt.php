<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-08-03 07:54:57
 */

namespace Diepxuan\Catalog\Http\Livewire\Component;

use Diepxuan\Catalog\Models\InDmVt;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Modelable;
use Livewire\Component;

class InputIndmvt extends Component
{
    #[Modelable]
    public $pMa_vt;
    protected $inDmVts;

    public function boot(): void
    {
        $this->inDmVts = InDmVt::select('ma_vt', 'ten_vt')->get();
    }

    public function mount(): void {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): \Closure|string|View
    {
        return view('catalog::components.input-indmvt', [
            'inDmVts' => $this->inDmVts,
        ]);
    }
}
