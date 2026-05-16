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

use Diepxuan\Simba\SModel\SModel;
use Diepxuan\Simba\StoredProcedures\AsPOGetDMCP;
use Diepxuan\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class PoDmCpIndex extends Component
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
        return view('catalog::muahang.po-dmcp-index', [
            'items' => $this->getItemsPaginated(),
        ])->layout('catalog::layouts.app');
    }

    protected function getItemsPaginated(): LengthAwarePaginatorContract
    {
        $results = AsPOGetDMCP::call([
            'pMa_cty' => SModel::CTY,
            'pMa_cp'  => '' !== $this->search ? $this->search : null,
            'pStruct' => '0',
        ]);

        $results = $this->normalizeRows($results);

        if ('' !== $this->search) {
            $results = $this->filterSearchResults($results);
        }

        return $this->paginateCollection($results);
    }

    protected function normalizeRows($results): Collection
    {
        return collect($results)->map(static fn ($item) => (object) [
            'ma_cp'  => $item->ma_cp ?? $item->MA_CP ?? '',
            'ten_cp' => $item->ten_cp ?? $item->TEN_CP ?? '',
            'tt_pb'  => $item->tt_pb ?? $item->TT_PB ?? '',
            'ksd'    => (bool) ($item->ksd ?? $item->KSD ?? false),
        ]);
    }

    protected function filterSearchResults(Collection $results): Collection
    {
        $search = mb_strtolower($this->search);

        return $results->filter(static function ($item) use ($search): bool {
            foreach ([$item->ma_cp, $item->ten_cp, $item->tt_pb] as $field) {
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
