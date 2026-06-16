<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-06-16 10:00:00
 */

namespace Diepxuan\Catalog\Http\Livewire\Home;

use Illuminate\View\View;
use Livewire\Component;

class Dashboard extends Component
{
    /**
     * Render the component.
     */
    public function render(): View
    {
        return view('catalog::home.dashboard')->layout('catalog::layouts.app');
    }
}
