<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-16 00:27:52
 */

namespace Diepxuan\Catalog\Http\Livewire\AR\Danhmuc;

use Diepxuan\Simba\SModel\SModel;
use Diepxuan\Simba\StoredProcedures\AsARGetDMNHKH;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Component Nhomkhachhang - Danh sách nhóm khách hàng.
 */
class Nhomkhachhang extends Component
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

    public function render(): View
    {
        return view('catalog::ar.danhmuc.nhomkhachhang', [
            'nhomkhachhangs' => $this->getNhomKhachHangsPaginated(),
        ])->layout('catalog::layouts.app');
    }

    protected function getNhomKhachHangsPaginated(): LengthAwarePaginatorContract
    {
        $results = AsARGetDMNHKH::call([
            'pMa_cty' => SModel::CTY,
            'pStruct' => '0',
        ]);

        $results = $this->normalizeRows($results);

        if ('' !== $this->search) {
            $results = $this->filterSearchResults($results);
        }

        return $this->paginateCollection($results);
    }

    protected function normalizeRows(Collection $results): Collection
    {
        return $results->map(static fn ($item) => (object) [
            'ma_nhkh'  => $item->ma_nhkh ?? $item->MA_NHKH ?? '',
            'ten_nhkh' => $item->ten_nhkh ?? $item->TEN_NHKH ?? '',
            'ghi_chu'  => $item->ghi_chu ?? $item->GHI_CHU ?? '',
            'ksd'      => (bool) ($item->ksd ?? $item->KSD ?? false),
        ]);
    }

    protected function filterSearchResults(Collection $results): Collection
    {
        $search = mb_strtolower($this->search);

        return $results->filter(static function ($item) use ($search): bool {
            foreach ([$item->ma_nhkh, $item->ten_nhkh, $item->ghi_chu] as $field) {
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

        $pagedItems = collect($items)->slice($offset, $this->perPage)->values();

        return new LengthAwarePaginator(
            $pagedItems,
            $total,
            $this->perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()],
        );
    }
}
