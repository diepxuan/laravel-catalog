<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-07-21
 */

namespace Diepxuan\Catalog\Http\Livewire\Component;

use Diepxuan\Simba\SModel\SModel;
use Diepxuan\Simba\StoredProcedures\AsPOGetDMCP;
use Diepxuan\Catalog\Http\Livewire\Component\Concerns\HasKsdFilter;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Input autocomplete danh mục chi phí mua hàng (CP).
 *
 * Features:
 * - Search theo mã CP hoặc tên CP
 * - Hiển thị mã + tên + TK chi phí trong dropdown
 * - Support keyboard navigation (Alpine local filter, debounce 150ms)
 * - Support wire:model qua #[Modelable] cho parent binding
 * - Dispatch chiphi-updated event cho auto-fill use cases
 *
 * Nguồn tra cứu:
 *   - simba-docs/data/sysDAOInfo.md (PODMCP -> asPOGetDMCP)
 *   - simba-docs/procedures/PO/procedures.md (asPOGetDMCP signature)
 *   - SP wrapper: diepxuan/laravel-simba/src/StoredProcedures/AsPOGetDMCP.php
 *
 * Bảng PODMCP có cột: ma_cty, ma_cp, ten_cp, tk_cp, tk_cp_co, ma_nhcp,
 *   tk_thue_gtgt_mua, tk_thue_gtgt_ban, tk_thue_nk, tk_thue_xk, ksd.
 *
 * Lưu ý: Component này chỉ lookup/chọn; thêm/sửa/xóa thuộc task DM CP riêng.
 */
class InputChiphi extends Component
{
    use HasKsdFilter;

    #[Modelable]
    public ?string $value = null;

    /** Text hiển thị (tên CP). */
    public string $search = '';

    /** Placeholder text. */
    public string $placeholder = 'Chọn chi phí';

    /**
     * Cache danh sách CP trong lifecycle render.
     *
     * @var array<int, array<string, mixed>>|null
     */
    protected ?array $cpCache = null;

    public function mount(?string $value = null): void
    {
        if ($value !== null) {
            $this->value = $value;
            $this->refreshSearchFromValue();
        }
    }

    public function updatedValue($newValue): void
    {
        if ($newValue !== null && trim($newValue) !== '') {
            $row = $this->findOneByMaCp($newValue);
            $this->search = $row !== null ? (string) ($row['ten_cp'] ?? '') : $newValue;
        }
    }

    /**
     * Chọn CP từ dropdown.
     */
    public function selectCp(string $ma_cp, string $ten_cp): void
    {
        $this->value = $ma_cp;
        $this->search = $ten_cp;

        $this->dispatch('chiphi-updated', ma_cp: $ma_cp, ten_cp: $ten_cp);
    }

    /**
     * Đồng bộ selection khi parent set mã CP qua event.
     */
    #[On('chiphi-set')]
    public function setValue(?string $ma_cp = null): void
    {
        if ($ma_cp === null || trim($ma_cp) === '') {
            $this->value = null;
            $this->search = '';

            return;
        }

        $this->value = $ma_cp;
        $this->refreshSearchFromValue();
    }

    /**
     * Xóa selection.
     */
    public function clear(): void
    {
        $this->value = null;
        $this->search = '';

        $this->dispatch('chiphi-updated', ma_cp: null, ten_cp: null);
    }

    /**
     * Render component.
     */
    public function render(): View
    {
        return view('catalog::components.input-chiphi', [
            'cpList' => $this->cpOptions(),
        ]);
    }

    /**
     * @return array<int, array{ma_cp: string, ten_cp: string, tk_cp: string}>
     */
    protected function cpOptions(): array
    {
        if ($this->cpCache !== null) {
            return $this->cpCache;
        }

        $rows = AsPOGetDMCP::call([
            'pMa_cty' => SModel::CTY,
            'pMa_cp' => null,
            'pStruct' => '0',
        ])->all();

        $merged = [];
        foreach ($rows as $row) {
            $row = \is_array($row) ? $row : (array) $row;

            $ma = (string) ($row['ma_cp'] ?? '');
            if ($ma === '') {
                continue;
            }

            if (!self::isActiveRow($row)) {
                continue;
            }

            $merged[] = [
                'ma_cp' => $ma,
                'ten_cp' => (string) ($row['ten_cp'] ?? ''),
                'tk_cp' => (string) ($row['tk_cp'] ?? ''),
            ];
        }

        return $this->cpCache = $merged;
    }

    protected function findOneByMaCp(string $maCp): ?array
    {
        foreach ($this->cpOptions() as $row) {
            if ($row['ma_cp'] === $maCp) {
                return $row;
            }
        }

        return null;
    }

    protected function refreshSearchFromValue(): void
    {
        if ($this->value !== null) {
            $row = $this->findOneByMaCp($this->value);
            $this->search = $row !== null ? (string) ($row['ten_cp'] ?? '') : $this->value;
        }
    }
}
