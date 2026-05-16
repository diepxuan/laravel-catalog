<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-16 09:35:07
 */

namespace Diepxuan\Catalog\Http\Livewire\Co\Danhmuc;

use Diepxuan\Simba\SModel\SModel;
use Diepxuan\Simba\StoredProcedures\AsGetCOdd;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Dinhmucchitiet extends Component
{
    use WithPagination;

    public int $nam;
    public int $thang;
    public string $maSpct  = '';
    public string $tk      = '';
    protected int $perPage = 50;

    public function mount(): void
    {
        $this->nam   = (int) session('year', now()->year);
        $this->thang = (int) now()->month;
    }

    public function updated($name): void
    {
        if (\in_array($name, ['nam', 'thang', 'maSpct', 'tk'], true)) {
            $this->resetPage();
        }
    }

    public function render(): View
    {
        return view('catalog::co.danhmuc.dinhmucchitiet', ['items' => $this->getItemsPaginated()])
            ->layout('catalog::layouts.app')
        ;
    }

    protected function getItemsPaginated(): LengthAwarePaginatorContract
    {
        $rows = AsGetCOdd::call([
            'pMa_cty'  => SModel::CTY,
            'pNam'     => $this->nam,
            'pThang'   => $this->thang,
            'pMa_spct' => $this->maSpct,
            'pTk'      => $this->tk,
        ])->map(static fn ($item) => (object) [
            'ma_spct'  => $item->ma_spct ?? $item->MA_SPCT ?? '',
            'tk'       => $item->tk ?? $item->TK ?? '',
            'sl_dd_ck' => (float) ($item->sl_dd_ck ?? $item->SL_DD_CK ?? 0),
            'gt_dd_ck' => (float) ($item->gt_dd_ck ?? $item->GT_DD_CK ?? 0),
        ]);

        $page  = Paginator::resolveCurrentPage();
        $total = \count($rows);

        return new LengthAwarePaginator($rows->slice(($page - 1) * $this->perPage, $this->perPage)->values(), $total, $this->perPage, $page, ['path' => request()->url(), 'query' => request()->query()]);
    }
}
