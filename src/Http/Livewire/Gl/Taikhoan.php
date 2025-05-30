<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-05-28 12:51:49
 */

namespace Diepxuan\Catalog\Http\Livewire\Gl;

use Diepxuan\Catalog\Models\GlDmTk;
use Illuminate\View\View;
use Livewire\Component;

class Taikhoan extends Component
{
    public $glDmTks;

    public function mount(): void
    {
        // \Debugbar::info($this->timer);
        $this->glDmTks = GlDmTk::all();
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
        // diepxuan/laravel-catalog/resources/views/gl/taikhoan.blade.php
        return view('catalog::gl.taikhoan')->layout('catalog::layouts.app');
    }
}
