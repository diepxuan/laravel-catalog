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

namespace Diepxuan\Catalog\Http\Livewire\Cash\Danhmuc;

use Diepxuan\Simba\SModel\SModel;
use Diepxuan\Simba\StoredProcedures\AsCADelDMKU;
use Diepxuan\Simba\StoredProcedures\AsCAGetDMKU;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Kheuoc extends Component
{
    use WithPagination;

    public string $search = '';

    protected int $perPage = 50;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function deleteItem(string $maKu): void
    {
        try {
            AsCADelDMKU::call([
                'pMa_cty' => SModel::CTY,
                'pMa_ku'  => $maKu,
            ]);
            $this->dispatch('success', message: 'Đã xóa khế ước ' . $maKu);
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Không thể xóa khế ước: ' . $e->getMessage());
        }
    }

    public function render(): View
    {
        return view('catalog::cash.danhmuc.kheuoc', [
            'items' => $this->getItemsPaginated(),
        ])->layout('catalog::layouts.app');
    }

    protected function getItemsPaginated(): LengthAwarePaginatorContract
    {
        $results = $this->normalizeRows(AsCAGetDMKU::call([]));

        if ('' !== $this->search) {
            $results = $this->filterSearchResults($results);
        }

        return $this->paginateCollection($results);
    }

    protected function normalizeRows(Collection $results): Collection
    {
        return $results->map(static fn ($item) => (object) [
            'ma_ku'    => $item->ma_ku ?? $item->MA_KU ?? '',
            'ten_ku'   => $item->ten_ku ?? $item->TEN_KU ?? '',
            'so_ku'    => $item->so_ku ?? $item->SO_KU ?? '',
            'ngay_vay' => $item->ngay_vay ?? $item->NGAY_VAY ?? null,
            'ngay_dh'  => $item->ngay_dh ?? $item->NGAY_DH ?? null,
            'ma_nt'    => $item->ma_nt ?? $item->MA_NT ?? '',
            'tien_nt'  => (float) ($item->tien_nt ?? $item->TIEN_NT ?? 0),
            'tien'     => (float) ($item->tien ?? $item->TIEN ?? 0),
            'ksd'      => (bool) ($item->ksd ?? $item->KSD ?? false),
        ]);
    }

    protected function filterSearchResults(Collection $results): Collection
    {
        $search = mb_strtolower($this->search);

        return $results->filter(static function ($item) use ($search): bool {
            foreach ([$item->ma_ku, $item->ten_ku, $item->so_ku] as $field) {
                if (str_contains(mb_strtolower((string) $field), $search)) {
                    return true;
                }
            }

            return false;
        })->values();
    }

    protected function paginateCollection($items): LengthAwarePaginatorContract
    {
        $page   = Paginator::resolveCurrentPage();
        $total  = \count($items);
        $offset = ($page - 1) * $this->perPage;

        return new LengthAwarePaginator(
            collect($items)->slice($offset, $this->perPage)->values(),
            $total,
            $this->perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()],
        );
    }
}
