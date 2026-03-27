<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-03-27 23:36:19
 */

namespace Diepxuan\Catalog\Http\Livewire\Home;

use Diepxuan\Catalog\Services\CatalogService;
use Illuminate\View\View;
use Livewire\Component;

class Dashboard extends Component
{
    public $menuTree;

    public function mount(): void
    {
        $catalogService = new CatalogService();
        $this->menuTree = $catalogService->menuTree();
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        return view('catalog::home.dashboard')->layout('catalog::layouts.app');
    }
}
