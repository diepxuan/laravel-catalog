<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-16 00:28:07
 */

namespace Diepxuan\Catalog\Http\Livewire\System;

use Diepxuan\Catalog\Config\SimbaProcessRegistry;
use Illuminate\View\View;
use Livewire\Component;

class SimbaProcessIndex extends Component
{
    public string $routeName = '';
    public array $metadata   = [];

    public function mount(string $routeName): void
    {
        $this->routeName = $routeName;
        $this->metadata  = SimbaProcessRegistry::get($routeName) ?? abort(404);
    }

    public function render(): View
    {
        return view('catalog::system.simba-process-index')->layout('catalog::layouts.app');
    }
}
