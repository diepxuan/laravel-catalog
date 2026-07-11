<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-08-02 23:53:24
 */

namespace Diepxuan\Catalog\Http\Livewire\Component;

use Diepxuan\Simba\StoredProcedures\AsINGetDMKHO;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Modelable;
use Livewire\Component;

class InputIndmkho extends Component
{
    #[Modelable]
    public $pMa_kho;
    protected Collection $inDmKhos;

    public function boot(): void
    {
        $this->inDmKhos = AsINGetDMKHO::call([
            'pMa_Cty' => \CatalogService::company()->id,
            'pMa_kho' => null,
            'pStruct' => null,
        ]);
    }

    public function mount(): void {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): \Closure|string|View
    {
        return view('catalog::components.input-indmkho', [
            'inDmKhos' => $this->warehouseOptions(),
        ]);
    }

    /**
     * Danh sách rút gọn cho Alpine local search.
     *
     * @return array<int, array{ma_kho: string, ten_kho: string}>
     */
    protected function warehouseOptions(): array
    {
        return $this->inDmKhos
            ->map(static fn ($item): array => [
                'ma_kho'  => (string) ($item->ma_kho ?? $item->MA_KHO ?? ''),
                'ten_kho' => (string) ($item->ten_kho ?? $item->TEN_KHO ?? ''),
            ])
            ->filter(static fn (array $item): bool => '' !== $item['ma_kho'])
            ->values()
            ->all();
    }
}
