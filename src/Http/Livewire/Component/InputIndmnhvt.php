<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-07-06 01:40:50
 */

namespace Diepxuan\Catalog\Http\Livewire\Component;

use Diepxuan\Simba\StoredProcedures\AsINGetDMNHVT;
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
        $this->inDmNhvts = AsINGetDMNHVT::call([
            'pMa_cty'  => \CatalogService::company()->id,
            'pMa_nhvt' => null,
            'pStruct'  => null,
        ]);
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
