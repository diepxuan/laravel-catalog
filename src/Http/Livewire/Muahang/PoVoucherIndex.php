<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-16 00:28:05
 */

namespace Diepxuan\Catalog\Http\Livewire\Muahang;

use Diepxuan\Catalog\Config\PoVoucherRegistry;
use Diepxuan\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class PoVoucherIndex extends Component
{
    public string $voucherCode = '';
    public array $metadata     = [];
    public string $pSearch     = '';
    public ?string $pMa_Kh     = null;
    public int $pPageIndex     = 1;
    public int $pPageSize      = 50;

    protected $vouchers;

    public function mount(string $voucherCode): void
    {
        $this->voucherCode = strtoupper($voucherCode);
        $this->metadata    = PoVoucherRegistry::get($this->voucherCode) ?? abort(404);
        $this->loadData();
    }

    public function updated($property): void
    {
        if (str_starts_with($property, 'p')) {
            $this->pPageIndex = 1;
            $this->loadData();
        }
    }

    public function loadData(): void
    {
        $modelClass = $this->metadata['model'];

        $this->vouchers = $modelClass::filterBySearch($this->pSearch)
            ->filterByMaKh($this->pMa_Kh)
            ->filterByNgayCt(\CatalogService::timerFrom(), \CatalogService::timerTo())
            ->where('ma_ct', $this->voucherCode)
            ->orderByDesc('ngay_ct')
            ->orderByDesc('so_ct')
            ->paginate($this->pPageSize, ['*'], 'page', $this->pPageIndex)
        ;
    }

    public function resetFilters(): void
    {
        $this->pSearch    = '';
        $this->pMa_Kh     = null;
        $this->pPageIndex = 1;

        \CatalogService::timer(['id' => 't' . str_pad((string) now()->month, 2, '0', STR_PAD_LEFT)]);

        $this->loadData();
    }

    public function render(): View
    {
        $vouchers = $this->vouchers instanceof Collection
            ? $this->vouchers
            : Collection::make($this->vouchers->items() ?? []);

        return view('catalog::muahang.po-voucher-index', [
            'vouchers'  => $vouchers,
            'paginator' => $this->vouchers,
        ])->layout('catalog::layouts.app');
    }
}
