<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-07-09
 */

namespace Diepxuan\Catalog\Http\Livewire\Component;

use Diepxuan\Simba\StoredProcedures\AsINGetDMVT;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Modelable;
use Livewire\Component;

class InputIndmvt extends Component
{
    #[Modelable]
    public $pMa_vt;
    protected Collection $inDmVts;

    public function boot(): void
    {
        $this->inDmVts = AsINGetDMVT::call([
            'pMa_cty'   => \CatalogService::company()->id,
            'pMa_vt'    => null,
            'pStruct'   => null,
            'pLanguage' => null,
        ]);
    }

    public function mount(): void {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): \Closure|string|View
    {
        return view('catalog::components.input-indmvt', [
            'inDmVts' => $this->itemOptions(),
        ]);
    }

    /**
     * Danh sách rút gọn cho Alpine local search.
     *
     * @return array<int, array{ma_vt: string, ten_vt: string}>
     */
    protected function itemOptions(): array
    {
        return $this->inDmVts
            ->map(static fn ($item): array => [
                'ma_vt'  => (string) ($item->ma_vt ?? $item->MA_VT ?? ''),
                'ten_vt' => (string) ($item->ten_vt ?? $item->TEN_VT ?? ''),
            ])
            ->filter(static fn (array $item): bool => '' !== $item['ma_vt'])
            ->values()
            ->all();
    }
}
