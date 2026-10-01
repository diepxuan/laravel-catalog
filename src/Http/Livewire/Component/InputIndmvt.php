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

    /**
     * Giá trị hiển thị ban đầu ở lần render đầu (SSR), do parent truyền `:value`.
     *
     * Livewire chỉ seed prop `#[Modelable]` từ parent trên các request kế tiếp;
     * lần render đầu cần param tường minh để Alpine hiển thị đúng mã VT
     * khi mở form sửa chứng từ.
     */
    public ?string $value = null;

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
        // Giữ $value bám theo lựa chọn hiện tại để khi component được tái tạo
        // (wire:key đổi) màn hình vẫn hiển thị mã mới nhất, không phải giá trị
        // ban đầu đã lỗi thời.
        if (\is_string($this->pMa_vt) && '' !== $this->pMa_vt) {
            $this->value = $this->pMa_vt;
        }

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
