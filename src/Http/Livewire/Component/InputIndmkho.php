<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-08-02 23:53:24
 */

namespace Diepxuan\Catalog\Http\Livewire\Component;

use Diepxuan\Catalog\Models\InDmKho;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Modelable;
use Livewire\Component;

class InputIndmkho extends Component
{
    #[Modelable]
    public $pMa_kho;
    protected $inDmKhos;

    public function boot(): void
    {
        $this->inDmKhos = InDmKho::getAsINGetDMKHO();
    }

    public function mount(): void {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): \Closure|string|View
    {
        return view('catalog::components.input-indmkho', [
            'inDmKhos' => $this->inDmKhos,
        ]);
    }
}
