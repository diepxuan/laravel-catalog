<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-16 00:28:04
 */

namespace Diepxuan\Catalog\Http\Livewire\Muahang;

use Diepxuan\Simba\Models\PoPh3;
use Diepxuan\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Danh sách hóa đơn mua hàng (PO3).
 */
class Hoadonmua extends Component
{
    public string $pSearch = '';
    public ?string $pMa_Kh = null;
    public int $pPageIndex = 1;
    public int $pPageSize  = 50;

    protected $invoices;

    public function mount(): void
    {
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
        $this->invoices = PoPh3::filterBySearch($this->pSearch)
            ->filterByMaKh($this->pMa_Kh)
            ->filterByNgayCt(\CatalogService::timerFrom(), \CatalogService::timerTo())
            ->orderByDesc('ngay_ct')
            ->orderByDesc('so_hd')
            ->paginate($this->pPageSize, ['*'], 'page', $this->pPageIndex)
        ;
    }

    public function resetFilters(): void
    {
        $this->pSearch    = '';
        $this->pMa_Kh     = null;
        $this->pPageIndex = 1;

        // Reset timer to current month
        \CatalogService::timer(['id' => 't' . str_pad((string) now()->month, 2, '0', STR_PAD_LEFT)]);

        $this->loadData();
    }

    public function render(): View
    {
        $invoices = $this->invoices instanceof Collection
            ? $this->invoices
            : Collection::make($this->invoices->items() ?? []);

        return view('catalog::muahang.hoadonmua', [
            'invoices'  => $invoices,
            'paginator' => $this->invoices,
        ])->layout('catalog::layouts.app');
    }
}
