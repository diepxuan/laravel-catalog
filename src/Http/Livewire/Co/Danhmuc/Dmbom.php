<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-16 09:35:08
 */

namespace Diepxuan\Catalog\Http\Livewire\Co\Danhmuc;

use Diepxuan\Simba\SModel\SModel;
use Diepxuan\Simba\StoredProcedures\AsCOGetDMCoBomCT;
use Diepxuan\Simba\StoredProcedures\AsCOGetDMCoBomPH;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Dmbom extends Component
{
    use WithPagination;

    public string $search          = '';
    public ?string $selectedMaSpct = null;
    protected int $perPage         = 50;

    protected $queryString = ['search' => ['except' => ''], 'selectedMaSpct' => ['except' => null]];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function selectBom(string $maSpct): void
    {
        $this->selectedMaSpct = $maSpct;
    }

    public function render(): View
    {
        return view('catalog::co.danhmuc.dmbom', [
            'headers' => $this->getHeadersPaginated(),
            'details' => $this->getDetails(),
        ])->layout('catalog::layouts.app');
    }

    protected function getHeadersPaginated(): LengthAwarePaginatorContract
    {
        $rows = AsCOGetDMCoBomPH::call(['pMa_cty' => SModel::CTY, 'pma_spct' => '']);
        $rows = $rows->map(static fn ($item) => (object) [
            'ma_spct'       => $item->ma_spct ?? $item->MA_SPCT ?? '',
            'ten_spct'      => $item->ten_spct ?? $item->TEN_SPCT ?? '',
            'ngay1'         => $item->ngay1 ?? $item->NGAY1 ?? null,
            'ngay2'         => $item->ngay2 ?? $item->NGAY2 ?? null,
            'don_gia_luong' => (float) ($item->don_gia_luong ?? $item->DON_GIA_LUONG ?? 0),
        ]);
        if ('' !== $this->search) {
            $search = mb_strtolower($this->search);
            $rows   = $rows->filter(static fn ($item) => str_contains(mb_strtolower((string) $item->ma_spct), $search) || str_contains(mb_strtolower((string) $item->ten_spct), $search))->values();
        }

        return $this->paginateCollection($rows);
    }

    protected function getDetails(): Collection
    {
        if (!$this->selectedMaSpct) {
            return collect();
        }

        return AsCOGetDMCoBomCT::call(['pMa_cty' => SModel::CTY, 'pma_spct' => $this->selectedMaSpct, 'pMa_vt' => ''])
            ->map(static fn ($item) => (object) [
                'ma_vt'    => $item->ma_vt ?? $item->MA_VT ?? '',
                'ten_vt'   => $item->ten_vt ?? $item->TEN_VT ?? '',
                'so_luong' => (float) ($item->so_luong ?? $item->SO_LUONG ?? 0),
                'vt_chinh' => (bool) ($item->vt_chinh ?? $item->VT_CHINH ?? false),
            ])
        ;
    }

    protected function paginateCollection($items): LengthAwarePaginatorContract
    {
        $page  = Paginator::resolveCurrentPage();
        $total = \count($items);

        return new LengthAwarePaginator(collect($items)->slice(($page - 1) * $this->perPage, $this->perPage)->values(), $total, $this->perPage, $page, ['path' => request()->url(), 'query' => request()->query()]);
    }
}
