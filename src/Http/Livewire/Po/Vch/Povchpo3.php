<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-07-18
 */

namespace Diepxuan\Catalog\Http\Livewire\Po\Vch;

use Diepxuan\Support\Collection;
use Diepxuan\Simba\StoredProcedures\AsPOGetPH3;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Danh sách hóa đơn mua hàng (PO3).
 *
 * Mapping:
 * - menuID   : `10.10.14` (sysMenu).
 * - DLL      : `POVchPO3.dll` (form `frmPOVchPO3`).
 * - SP       : `asPOGetPH3` (header) + `asPOGetPO3` (chi tiet).
 * - Route    : `_simba-source/po/vch/povchpo3` (slug khong suffix menuid vi PO3 la 1-1 sysMenu).
 *
 * Refactor tu `Muahang\Hoadonmua` (dead code) sang pattern chuan `Po\Vch\Povchpo3`
 * (giong `Po\Rpt\Arrptbccn01Sl`, PR #247). Bind SP `asPOGetPH3` thay vi Eloquent
 * `PoPh3` (giong task 008, 117).
 */
class Povchpo3 extends Component
{
    public const MA_CT = 'PO3';

    public string $pSearch = '';

    public ?string $pMa_kh = null;

    public int $pPageIndex = 1;

    public int $pPageSize = 50;

    public int $timerKey = 0;

    protected $invoices;

    public function mount(): void
    {
        $this->loadData();
    }

    public function updated($property): void
    {
        if (str_starts_with((string) $property, 'p')) {
            $this->pPageIndex = 1;
            $this->loadData();
        }
    }

    public function loadData(): void
    {
        $params = [
            'pMa_cty'  => \CatalogService::company()->id,
            'pMa_ct'   => self::MA_CT,
            'pStt_rec' => null,
            'pStruct'  => null,
        ];

        if ('' !== $this->pSearch) {
            $params['pSearch'] = $this->pSearch;
        }

        if (null !== $this->pMa_kh && '' !== $this->pMa_kh) {
            $params['pMa_kh'] = $this->pMa_kh;
        }

        $rows = AsPOGetPH3::call($params);

        $this->invoices = Collection::make($rows->all());
    }

    public function resetFilters(): void
    {
        $this->pSearch    = '';
        $this->pMa_kh     = null;
        $this->pPageIndex = 1;

        \CatalogService::timer(['id' => 't' . str_pad((string) now()->month, 2, '0', STR_PAD_LEFT)]);
        $this->timerKey++;

        $this->loadData();
    }

    public function render(): View
    {
        return view('catalog::po.vch.povchpo3', [
            'invoices' => $this->invoices,
        ])->layout('catalog::layouts.app');
    }
}
