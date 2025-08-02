<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-08-02 22:14:07
 */

namespace Diepxuan\Catalog\Http\Livewire\In;

use Diepxuan\Catalog\Models\InDmNhvt;
use Diepxuan\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class Dmnhvt extends Component
{
    public $pMa_nhvt;
    public $pStruct;
    protected $inDmNhvts;

    public function mount(): void
    {
        // \Debugbar::info([
        //     'pMa_Cty'  => \CatalogService::company()->id,
        //     'pMa_nhvt' => $this->pMa_nhvt ?? null,
        //     'pStruct'  => $this->pStruct ?? null,
        // ]);

        $collection = InDmNhvt::getAsINGetDMNHVT([
            'pMa_Cty'  => \CatalogService::company()->id,
            'pMa_nhvt' => $this->pMa_nhvt ?? null,
            'pStruct'  => $this->pStruct ?? null,
        ]);

        $this->inDmNhvts = $collection instanceof Collection
            ? $collection
            : Collection::make($collection->all());
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
        // diepxuan/laravel-catalog/resources/views/in/dmvt.blade.php
        return view('catalog::in.dmnhvt', [
            'inDmNhvts' => $this->inDmNhvts,
        ])->layout('catalog::layouts.app');
    }
}
