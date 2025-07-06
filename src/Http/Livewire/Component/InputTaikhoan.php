<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-07-06 19:06:53
 */

namespace Diepxuan\Catalog\Http\Livewire\Component;

use Diepxuan\Catalog\Models\GlDmTk;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class InputTaikhoan extends Component
{
    #[Modelable]
    public $pTk;
    protected $glDmTks;

    public function boot(): void
    {
        $this->glDmTks = GlDmTk::all();
    }

    public function mount(): void {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): \Closure|string|View
    {
        return view('catalog::components.input-taikhoan', [
            'glDmTks' => $this->glDmTks,
        ]);
    }
}
