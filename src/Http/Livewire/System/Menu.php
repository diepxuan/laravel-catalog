<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-08-11 23:29:54
 */

namespace Diepxuan\Catalog\Http\Livewire\System;

use Diepxuan\Catalog\Models\NavigationMenu;
use Illuminate\View\View;
use Livewire\Component;

class Menu extends Component
{
    public $menus;

    public function mount(): void
    {
        // \Debugbar::info($this->timer);
        $this->menus = NavigationMenu::all();
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
        // diepxuan/laravel-catalog/resources/views/system/menu.blade.php
        return view('catalog::system.menu')->layout('catalog::layouts.app');
    }
}
