<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-03-26 18:42:28
 */

namespace Diepxuan\Catalog\Http\Livewire\System;

use Diepxuan\Catalog\Models\System;
use Illuminate\View\View;
use Livewire\Component;

class Dashboard extends Component
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
        $this->dispatch('action-message', ['on' => 'khoa-so-updated']);
        // \Debugbar::info($this->system->khoaSo);
    }

    /**
     * Render the component.
     *
     * @return View
     */
    public function render()
    {
        return view('catalog::system.system')->layout('catalog::layouts.app');
    }
}
