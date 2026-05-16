<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-16 00:27:53
 */

namespace Diepxuan\Catalog\Http\Livewire\Banhang;

use Diepxuan\Catalog\Config\SimbaReportRegistry;
use Illuminate\View\View;
use Livewire\Component;

class ArReportIndex extends Component
{
    public string $routeName = '';
    public array $metadata   = [];

    public function mount(string $routeName): void
    {
        $this->routeName = $routeName;
        $this->metadata  = SimbaReportRegistry::get($routeName) ?? abort(404);
    }

    public function render(): View
    {
        return view('catalog::banhang.ar-report-index')->layout('catalog::layouts.app');
    }
}
