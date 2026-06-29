<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Http\Livewire\In\Dict;

use Illuminate\View\View;
use Livewire\Component;

class IndmvtPage extends Component
{
    public function render(): View
    {
        return view('catalog::in.dict.indmvt')->layout('catalog::layouts.app');
    }
}
