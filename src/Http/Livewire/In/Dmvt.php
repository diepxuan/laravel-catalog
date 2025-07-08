<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-07-08 12:29:36
 */

namespace Diepxuan\Catalog\Http\Livewire\In;

use Diepxuan\Catalog\Models\InDmVt;
use Diepxuan\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class Dmvt extends Component
{
    protected $inDmVts;

    public function mount(): void
    {
        /** @var Collection $collection */
        $collection = InDmVt::all();

        // Nếu thật sự cần ép kiểu (cast), bạn có thể viết như sau:
        $this->inDmVts = $collection instanceof Collection
            ? $collection
            : Collection::make($collection->all());

        // \Debugbar::info($this->timer);
        // $this->inDmVts = (\Diepxuan\Support\Collection) InDmVt::all();
        // $this->inDmVts = InDmVt::limit(100)->get();
        // $this->inDmVts = InDmVt::withQuantity()
        //     ->orderBy('InDmVt.ma_vt', 'asc')
        // // ->take($this->perPage)
        //     ->get()
        // ;
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
        return view('catalog::in.dmvt', [
            'inDmVts' => $this->inDmVts->toMarkdownTable([
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
                'tinh_ton_kho' => 'Tính tồn kho',
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
            ]),
            // 'inDmVts' => $this->inDmVts,
        ])->layout('catalog::layouts.app');
    }
}
