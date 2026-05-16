<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-16 09:35:06
 */

namespace Diepxuan\Catalog\Http\Livewire\AR\Danhmuc;

use Diepxuan\Simba\SModel\SModel;
use Diepxuan\Simba\StoredProcedures\AsARDelDMPLKH;
use Diepxuan\Simba\StoredProcedures\AsARGetDMPLKH;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Phanloaikhachhang extends Component
{
    use WithPagination;

    public string $search   = '';
    public ?int $filterLoai = null;

    protected int $perPage = 50;

    protected $queryString = [
        'search'     => ['except' => ''],
        'filterLoai' => ['except' => null],
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function deleteItem(string $maPlkh, int $loai): void
    {
        try {
            AsARDelDMPLKH::call([
                'pMa_cty'  => SModel::CTY,
                'pMa_plkh' => $maPlkh,
                'pLoai'    => $loai,
            ]);

            $this->dispatch('success', message: 'Đã xóa phân loại khách hàng ' . $maPlkh);
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Không thể xóa phân loại khách hàng: ' . $e->getMessage());
        }
    }

    public function render(): View
    {
        return view('catalog::ar.danhmuc.phanloaikhachhang', [
            'items' => $this->getItemsPaginated(),
        ])->layout('catalog::layouts.app');
    }

    protected function getItemsPaginated(): LengthAwarePaginatorContract
    {
        $results = AsARGetDMPLKH::call([
            'pMa_cty' => SModel::CTY,
            'pStruct' => '0',
        ]);

        $results = $this->normalizeRows($results);

        if ('' !== $this->search) {
            $results = $this->filterSearchResults($results);
        }

        if (null !== $this->filterLoai) {
            $results = $results->filter(static fn ($item) => (int) ($item->loai ?? 0) === $this->filterLoai)->values();
        }

        return $this->paginateCollection($results);
    }

    protected function normalizeRows(Collection $results): Collection
    {
        return $results->map(static fn ($item) => (object) [
            'ma_plkh'  => $item->ma_plkh ?? $item->MA_PLKH ?? '',
            'ten_plkh' => $item->ten_plkh ?? $item->TEN_PLKH ?? '',
            'loai'     => (int) ($item->loai ?? $item->LOAI ?? 0),
            'ghi_chu'  => $item->ghi_chu ?? $item->GHI_CHU ?? '',
            'ksd'      => (bool) ($item->ksd ?? $item->KSD ?? false),
        ]);
    }

    protected function filterSearchResults(Collection $results): Collection
    {
        $search = mb_strtolower($this->search);

        return $results->filter(static function ($item) use ($search): bool {
            foreach ([$item->ma_plkh, $item->ten_plkh, $item->ghi_chu] as $field) {
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
