<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-16 00:28:02
 */

namespace Diepxuan\Catalog\Http\Livewire;

use Diepxuan\Catalog\Config\FinanceVoucherRegistry;
use Illuminate\View\View;
use Livewire\Component;

class FinanceVoucherIndex extends Component
{
    public string $voucherCode = '';
    public array $metadata     = [];

    public function mount(string $voucherCode): void
    {
        $this->voucherCode = strtoupper($voucherCode);
        $this->metadata    = FinanceVoucherRegistry::get($this->voucherCode) ?? abort(404);
    }

    public function render(): View
    {
        return view('catalog::finance-voucher-index')->layout('catalog::layouts.app');
    }
}
