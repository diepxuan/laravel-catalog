<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-08-08 15:46:26
 */

namespace Diepxuan\Catalog\Http\Livewire\Component;

use Diepxuan\Catalog\Models\InDmNhvt;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Modelable;
use Livewire\Component;

class InputIndmnhvt extends Component
{
    #[Modelable]
    public $pMa_nhvt;
    protected $inDmNhvts;

    public function boot(): void
    {
        $this->inDmNhvts = InDmNhvt::select('ma_nhvt', 'ten_nhvt')->get();
    }

    public function mount(): void {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): \Closure|string|View
    {
        return view('catalog::components.input-indmnhvt', [
            'inDmNhvts' => $this->inDmNhvts,
        ]);
    }
}
