<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-07-19
 */

namespace Diepxuan\Catalog\Http\Livewire\Component;

use Diepxuan\Simba\SModel\SModel;
use Diepxuan\Simba\StoredProcedures\AsGetDMHTTT;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Input autocomplete hình thức thanh toán (HTTT).
 *
 * Features:
 * - Search theo mã HTTT hoặc tên HTTT
 * - Hiển thị mã + tên + TK phải trả trong dropdown
 * - Support keyboard navigation (Alpine local filter, debounce 150ms)
 *
 * Nguồn tra cứu:
 *   - simba-docs/data/sysDAOInfo.md (SIDMHTTT -> asSIGetDMHTTT)
 *   - simba-docs/procedures/SI/procedures.md (asSIGetDMHTTT signature)
 *   - SP wrapper: diepxuan/laravel-simba/src/StoredProcedures/AsGetDMHTTT.php
 *
 * Bảng SIDMHTTT có cột: ma_cty, ma_httt, moduleid, ten_httt, tk,
 *   tk_thue_gtgt_mua, tk_thue_gtgt_ban, tk_thue_nk, tk_thue_xk,
 *   tk_gtgt_nk_no, tk_gtgt_nk_co, tk_thue_gtgt_xk, tk_thue_ttdb, tk_ck, ksd.
 *
 * Lưu ý: Component này chỉ lookup/chọn; thêm/sửa/xóa thuộc task DM HTTT riêng.
 */
class InputHttt extends Component
{
    /** Giá trị selected (mã HTTT). */
    public ?string $value = null;

    /** Text hiển thị (tên HTTT). */
    public string $search = '';

    /** Placeholder text. */
    public string $placeholder = 'Chọn HTTT';

    /** Module filter (PO/SI/AR; null = tất cả). */
    public ?string $moduleId = null;

    /**
     * Cache danh sách HTTT trong lifecycle render.
     *
     * @var array<int, array<string, mixed>>|null
     */
    protected ?array $htttCache = null;

    public function mount(?string $value = null, ?string $moduleId = null): void
    {
        $this->value = $value;
        $this->moduleId = $moduleId;

        if ($value) {
            $row = $this->findOneByMaHttt($value);
            if ($row !== null) {
                $this->search = (string) ($row['ten_httt'] ?? '');
            }
        }
    }

    /**
     * Chọn HTTT từ dropdown.
     */
    public function selectHttt(string $ma_httt, string $ten_httt): void
    {
        $this->value = $ma_httt;
        $this->search = $ten_httt;

        $this->dispatch('httt-updated', ma_httt: $ma_httt, ten_httt: $ten_httt);
    }

    /**
     * Đồng bộ selection khi parent set mã HTTT (vd: auto-chọn theo NCC).
     *
     * Parent dispatch `httt-set` (kèm ma_httt) → component refresh `value` + text
     * hiển thị. Không re-dispatch `httt-updated` để tránh vòng lặp với parent.
     */
    #[On('httt-set')]
    public function setValue(?string $ma_httt = null): void
    {
        if ($ma_httt === null || trim($ma_httt) === '') {
            $this->value = null;
            $this->search = '';

            return;
        }

        $this->value = $ma_httt;
        $row = $this->findOneByMaHttt($ma_httt);
        $this->search = $row !== null ? (string) ($row['ten_httt'] ?? '') : $ma_httt;
    }

    /**
     * Xóa selection.
     */
    public function clear(): void
    {
        $this->value = null;
        $this->search = '';

        $this->dispatch('httt-updated', ma_httt: null, ten_httt: null);
    }

    /**
     * Render component.
     */
    public function render(): View
    {
        return view('catalog::components.input-httt', [
            'htttList' => $this->htttOptions(),
        ]);
    }

    /**
     * @return array<int, array{ma_httt: string, ten_httt: string, tk: string, tk_thue_gtgt_mua: string}>
     */
    protected function htttOptions(): array
    {
        if ($this->htttCache !== null) {
            return $this->htttCache;
        }

        $rows = AsGetDMHTTT::call([
            'pMa_cty' => SModel::CTY,
            'pMa_httt' => null,
            'pModuleid' => $this->moduleId,
            'pStruct' => '0',
        ])->all();

        $merged = [];
        foreach ($rows as $row) {
            $row = \is_array($row) ? $row : (array) $row;

            $ma = (string) ($row['ma_httt'] ?? '');
            if ($ma === '') {
                continue;
            }

            $ksd = (int) ($row['ksd'] ?? 0);
            if ($ksd === 1) {
                continue;
            }

            $merged[] = [
                'ma_httt' => $ma,
                'ten_httt' => (string) ($row['ten_httt'] ?? ''),
                'tk' => (string) ($row['tk'] ?? ''),
                'tk_thue_gtgt_mua' => (string) ($row['tk_thue_gtgt_mua'] ?? ''),
            ];
        }

        return $this->htttCache = $merged;
    }

    protected function findOneByMaHttt(string $maHttt): ?array
    {
        foreach ($this->htttOptions() as $row) {
            if ($row['ma_httt'] === $maHttt) {
                return $row;
            }
        }

        return null;
    }
}
