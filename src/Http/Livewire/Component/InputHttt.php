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

use Diepxuan\Catalog\Http\Livewire\Component\Concerns\HasKsdFilter;
use Diepxuan\Simba\SModel\SModel;
use Diepxuan\Simba\StoredProcedures\AsGetDMHTTT;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Input autocomplete hình thức thanh toán (HTTT).
 *
 * Convention (khoi dong bo voi InputChiphi, InputNgoaite):
 *  - `#[Modelable]` de parent bind qua `wire:model`.
 *  - Dispatch `{slug}-updated` khi user chon/xoa de parent auto-fill (vd HTTT -> tk_pt/tk_thue).
 *  - Dong bo `search` khi value doi (parent reset NCC, ..).
 *
 * Nguồn tra cứu:
 *   - simba-docs/data/sysDAOInfo.md (SIDMHTTT -> asSIGetDMHTTT)
 *   - simba-docs/procedures/SI/procedures.md (asSIGetDMHTTT signature)
 *   - SP wrapper: diepxuan/laravel-simba/src/StoredProcedures/AsGetDMHTTT.php
 *
 * Bang SIDMHTTT: ma_cty, ma_httt, moduleid, ten_httt, tk, tk_thue_gtgt_mua, ksd.
 *
 * Luu y: Component nay chi lookup/chon; them/sua/xoa thuoc task DM HTTT rieng.
 */
class InputHttt extends Component
{
    use HasKsdFilter;

    #[Modelable]
    public ?string $value = null;

    /** Text hien thi (ten HTTT). */
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
        $this->moduleId = $moduleId;

        if ($value !== null) {
            $this->value = $value;
            $this->refreshSearchFromValue();
        }
    }

    /**
     * Dong bo `search` khi parent gan `value` qua wire:model.
     */
    public function updatedValue(mixed $newValue): void
    {
        if ($newValue === null || trim((string) $newValue) === '') {
            $this->search = '';

            return;
        }

        $row = $this->findOneByMaHttt((string) $newValue);
        $this->search = $row !== null ? (string) ($row['ten_httt'] ?? '') : (string) $newValue;
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
     * Dong bo selection khi parent dispatch `httt-set` (vd auto-chon theo NCC).
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
        $this->refreshSearchFromValue();
    }

    /**
     * Xoa selection.
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

            if (!self::isActiveRow($row)) {
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

    protected function refreshSearchFromValue(): void
    {
        if ($this->value !== null) {
            $row = $this->findOneByMaHttt($this->value);
            $this->search = $row !== null ? (string) ($row['ten_httt'] ?? '') : $this->value;
        }
    }
}
