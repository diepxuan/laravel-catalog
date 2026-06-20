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

namespace Diepxuan\Catalog\Http\Livewire\Po\Dict;

use Diepxuan\Simba\SModel\SModel;
use Diepxuan\Simba\StoredProcedures\AsARGetDMKH;
use Diepxuan\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Ardmkh extends Component
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

    public function deleteDoiTuong(string $maKh): void
    {
        $nhaCungCap = \Diepxuan\Catalog\Models\ArDmKh::withoutGlobalScopes()
            ->where('ma_kh', $maKh)
            ->first()
        ;

        if (!$nhaCungCap) {
            $this->dispatch('error', message: 'Không tìm thấy nhà cung cấp.');

            return;
        }

        if ($nhaCungCap->hasTransactions()) {
            $this->dispatch('error', message: 'Không thể xóa nhà cung cấp đã có giao dịch.');

            return;
        }

        try {
            \Diepxuan\Simba\StoredProcedures\AsARDelDMKH::call([
                'pMa_cty' => SModel::CTY,
                'pMa_kh'  => $maKh,
            ]);
            $this->dispatch('success', message: 'Đã xóa nhà cung cấp ' . $maKh);
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Không thể xóa nhà cung cấp: ' . $e->getMessage());
        }
    }

    public function render(): View
    {
        return view('catalog::po.dict.ardmkh', [
            'arDmKhs' => $this->getSuppliersPaginated(),
        ])->layout('catalog::layouts.app');
    }

    protected function getSuppliersPaginated(): LengthAwarePaginatorContract
    {
        $results = AsARGetDMKH::getSuppliers(
            maCty: SModel::CTY,
            search: '' !== $this->search ? $this->search : null,
        );

        $results = $this->normalizeRows($results);

        if ('' !== $this->search) {
            $results = $this->filterSearchResults($results);
        }

        return $this->paginateCollection($results);
    }

    protected function normalizeRows($results): Collection
    {
        return new Collection(collect($results)->map(static fn ($item) => (object) [
            'ma_kh'      => $item->ma_kh ?? $item->MA_KH ?? '',
            'ten_kh'     => $item->ten_kh ?? $item->TEN_KH ?? '',
            'dia_chi'    => $item->dia_chi ?? $item->DIA_CHI ?? '',
            'tel'        => $item->tel ?? $item->TEL ?? '',
            'nguoi_gd'   => $item->nguoi_gd ?? $item->NGUOI_GD ?? '',
            'ma_httt_po' => $item->ma_httt_po ?? $item->MA_HTTT_PO ?? '',
            'ma_so_thue' => $item->ma_so_thue ?? $item->MA_SO_THUE ?? '',
            'ma_nhkh'    => $item->ma_nhkh ?? $item->MA_NHKH ?? '',
        ]));
    }

    protected function filterSearchResults(Collection $results): Collection
    {
        $search = mb_strtolower($this->search);

        return new Collection($results->filter(static function ($item) use ($search): bool {
            foreach ([$item->ma_kh, $item->ten_kh, $item->dia_chi, $item->tel, $item->ma_so_thue] as $field) {
                if (str_contains(mb_strtolower((string) $field), $search)) {
                    return true;
                }
            }

            return false;
        })->values());
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
