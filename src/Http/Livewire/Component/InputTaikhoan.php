<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-03-15 22:49:13
 */

namespace Diepxuan\Catalog\Http\Livewire\Component;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Modelable;
use Livewire\Component;

/**
 * Component input chọn tài khoản với search thông minh.
 *
 * Features:
 * - Search theo mã tài khoản hoặc tên tài khoản
 * - Hiển thị cả mã và tên tài khoản trong dropdown
 * - Dropdown styling đẹp
 * - Support keyboard navigation
 */
class InputTaikhoan extends Component
{
    #[Modelable]
    public $pTk;
    protected $glDmTks;

    public function boot(): void
    {
        $this->glDmTks = \CatalogService::glDmTks();
    }

    public function mount(): void {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): \Closure|string|View
    {
        return view('catalog::components.input-taikhoan', [
            'glDmTks' => $this->glDmTks,
        ]);
    }
}
