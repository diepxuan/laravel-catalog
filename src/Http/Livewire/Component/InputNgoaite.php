<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-07-20
 */

namespace Diepxuan\Catalog\Http\Livewire\Component;

use Diepxuan\Simba\SModel\SModel;
use Diepxuan\Simba\StoredProcedures\AsSIGetDMNT;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Input autocomplete danh mục ngoại tệ (NT).
 *
 * Features:
 * - Search theo mã NT hoặc tên NT
 * - Hiển thị mã + tên + tỷ giá trong dropdown
 * - Support keyboard navigation (Alpine local filter, debounce 150ms)
 * - Support wire:model qua #[Modelable] cho parent binding
 * - Dispatch ngoaite-updated event cho auto-fill ty_gia
 *
 * Nguồn tra cứu:
 *   - simba-docs/data/sysDAOInfo.md (SIDMNT -> asSIGetDMNT)
 *   - simba-docs/procedures/SI/procedures.md (asSIGetDMNT signature)
 *   - SP wrapper: diepxuan/laravel-simba/src/StoredProcedures/AsSIGetDMNT.php
 *
 * Bảng SIDMNT có cột: ma_cty, ma_nt, ten_nt, ky_hieu, ty_gia, decimals,
 *   mau_so, ksd, ngay_tg, lUser, dUser.
 *
 * Lưu ý: Component này chỉ lookup/chọn; thêm/sửa/xóa thuộc task DM NT riêng.
 * Khi chọn NT, parent nên auto-fill pTy_gia từ dữ liệu trả về.
 */
class InputNgoaite extends Component
{
    #[Modelable]
    public ?string $value = null;

    /** Text hiển thị (tên NT). */
    public string $search = '';

    /** Placeholder text. */
    public string $placeholder = 'Chọn ngoại tệ';

    /**
     * Cache danh sách NT trong lifecycle render.
     *
     * @var array<int, array<string, mixed>>|null
     */
    protected ?array $ntCache = null;

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
            $row = $this->findOneByMaNt($newValue);
            $this->search = $row !== null ? (string) ($row['ten_nt'] ?? '') : $newValue;
        }
    }

    /**
     * Chọn NT từ dropdown.
     */
    public function selectNt(string $ma_nt, string $ten_nt, float $ty_gia = 1): void
    {
        $this->value = $ma_nt;
        $this->search = $ten_nt;

        $this->dispatch('ngoaite-updated', ma_nt: $ma_nt, ten_nt: $ten_nt, ty_gia: $ty_gia);
    }

    /**
     * Đồng bộ selection khi parent set mã NT qua event.
     */
    #[On('ngoaite-set')]
    public function setValue(?string $ma_nt = null): void
    {
        if ($ma_nt === null || trim($ma_nt) === '') {
            $this->value = null;
            $this->search = '';

            return;
        }

        $this->value = $ma_nt;
        $this->refreshSearchFromValue();
    }

    /**
     * Xóa selection.
     */
    public function clear(): void
    {
        $this->value = null;
        $this->search = '';

        $this->dispatch('ngoaite-updated', ma_nt: null, ten_nt: null, ty_gia: 1);
    }

    /**
     * Render component.
     */
    public function render(): View
    {
        return view('catalog::components.input-ngoaite', [
            'ntList' => $this->ntOptions(),
        ]);
    }

    /**
     * @return array<int, array{ma_nt: string, ten_nt: string, ty_gia: float}>
     */
    protected function ntOptions(): array
    {
        if ($this->ntCache !== null) {
            return $this->ntCache;
        }

        $rows = AsSIGetDMNT::call([
            'pMa_cty' => SModel::CTY,
            'pMa_nt' => null,
            'pStruct' => '0',
        ])->all();

        $merged = [];
        foreach ($rows as $row) {
            $row = \is_array($row) ? $row : (array) $row;

            $ma = (string) ($row['ma_nt'] ?? '');
            if ($ma === '') {
                continue;
            }

            $ksd = (int) ($row['ksd'] ?? 0);
            if ($ksd === 1) {
                continue;
            }

            $merged[] = [
                'ma_nt' => $ma,
                'ten_nt' => (string) ($row['ten_nt'] ?? ''),
                'ty_gia' => (float) ($row['ty_gia'] ?? 1),
            ];
        }

        return $this->ntCache = $merged;
    }

    protected function findOneByMaNt(string $maNt): ?array
    {
        foreach ($this->ntOptions() as $row) {
            if ($row['ma_nt'] === $maNt) {
                return $row;
            }
        }

        return null;
    }

    protected function refreshSearchFromValue(): void
    {
        if ($this->value !== null) {
            $row = $this->findOneByMaNt($this->value);
            $this->search = $row !== null ? (string) ($row['ten_nt'] ?? '') : $this->value;
        }
    }
}
