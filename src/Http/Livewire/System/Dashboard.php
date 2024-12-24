<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-12-24 14:51:33
 */

namespace Diepxuan\Catalog\Http\Livewire\System;

use Diepxuan\Catalog\Models\System;
use Illuminate\View\View;
use Laravel\Jetstream\Http\Livewire\NavigationMenu as NavigationMenuComponent;
use Livewire\Component;

class Dashboard extends NavigationMenuComponent
{
    public $system;
    public $ngayKhoaSo;

    public function loadData(): void {}

    public function mount(): void
    {
        $this->system ??= System::first();
        $this->ngayKhoaSo = $this->system->khoaSo;
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateKhoaSo(): void
    {
        $this->system->khoaSo = $this->ngayKhoaSo;
        \Debugbar::info($this->system->khoaSo);
    }

    /**
     * Render the component.
     *
     * @return View
     */
    public function render()
    {
        return view('catalog::system.dashboard');
    }
}
