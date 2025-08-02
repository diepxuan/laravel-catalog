<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-08-02 22:13:45
 */

namespace Diepxuan\Catalog\Http\Livewire\In;

use Diepxuan\Catalog\Models\InDmKho;
use Diepxuan\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class Dmkho extends Component
{
    public $pMa_kho;
    public $pStruct;
    protected $inDmKhos;

    public function mount(): void
    {
        $collection = InDmKho::getAsINGetDMKHO([
            'pMa_Cty' => \CatalogService::company()->id,
            'pMa_kho' => $this->pMa_kho ?? null,
            'pStruct' => $this->pStruct ?? null,
        ]);

        $this->inDmKhos = $collection instanceof Collection
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
        return view('catalog::in.dmkho', [
            'inDmKhos' => $this->inDmKhos,
        ])->layout('catalog::layouts.app');
    }
}
