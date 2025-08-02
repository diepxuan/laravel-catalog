<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-08-02 19:12:55
 */

namespace Diepxuan\Catalog\Http\Livewire\In;

use Diepxuan\Catalog\Models\InDmNhvt;
use Diepxuan\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class Dmnhvt extends Component
{
    public $pMa_nhvt;
    public $pStruct;
    protected $inDmNhvts;

    public function mount(): void
    {
        $collection = InDmNhvt::getAsINGetDMNHVT([
            'pMa_Cty'  => \CatalogService::company()->id,
            'pMa_nhvt' => $this->pMa_nhvt ?? null,
            'pStruct'  => $this->pStruct ?? null,
        ]);

        $this->inDmNhvts = $collection instanceof Collection
            ? $collection
            : Collection::make($collection->all());
    }

    public function updated($property): void
    {
        // \Debugbar::info($property);
    }

    /**
     * Render the component.
     *
     * @return View
     */
    public function render()
    {
        // diepxuan/laravel-catalog/resources/views/in/dmvt.blade.php
        return view('catalog::in.dmnhvt', [
            'inDmVts' => $this->inDmNhvts->toMarkdownTable([
                'ma_vt'        => 'Mã vật tư',
                'ten_vt'       => 'Tên vật tư',
                'dvt'          => 'ĐVT',
                'ma_nhvt'      => 'Mã Nhóm',
                'ma_kho'       => 'Mã Kho',
                'ma_vitri'     => 'Mã vị trí',
                'tk_vt'        => 'TK VT',
                'tk_dt'        => 'TK DT',
                'tk_dtnb'      => 'TK DTNB',
                'tk_gv'        => 'TK GV',
                'tk_tl'        => 'TK TL',
                'tk_ck'        => 'TK CK',
                'ton_kho'      => 'Tính tồn kho',
                'loai'         => 'Loại',
                'ten_loai'     => 'Tên loại',
                'gia_ton'      => 'Giá tồn',
                'ten_loai_gia' => 'Tên loại giá',
                'tinh_gt'      => 'Tính giá thành',
                'ma_nhvat'     => 'Mã nhóm VAT',
                'nuoc_sx'      => 'Nước sản xuất',
                'ma_plvt1'     => 'Mã PL VT 1',
                'ma_plvt2'     => 'Mã PL VT 2',
                'ma_plvt3'     => 'Mã PL VT 3',
                'dvt_ban'      => 'ĐVT bán',
                'hs_dvtban'    => 'HS ĐVT bán',
                'dvt_mua'      => 'ĐVT mua',
                'hs_dvtmua'    => 'HS ĐVT mua',
                'ma_thue'      => 'Mã thuế',
                'sl_min'       => 'SL min',
                'sl_max'       => 'SL max',
                'nha_sx'       => 'Nhà SX',
                'nha_pp'       => 'Nhà PP',
                'ksd'          => 'KSD',
                'cdate'        => 'Ngày tạo',
                'cuser'        => 'Người tạo',
                'ldate'        => 'Ngày sửa',
                'luser'        => 'Người sửa',
            ], true),
            // 'inDmVts' => $this->inDmVts,
        ])->layout('catalog::layouts.app');
    }
}
