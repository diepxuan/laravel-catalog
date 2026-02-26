<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-02-25 22:56:00
 */

namespace Diepxuan\Catalog\Http\Livewire\System\Balance;

use Illuminate\View\View;
use Livewire\Component;

class AccountOpening extends Component
{
    /**
     * Render the component.
     *
     * @return View
     */
    public function render()
    {
        return view('catalog::system.balance.account-opening')->layout('catalog::layouts.app');
    }
}