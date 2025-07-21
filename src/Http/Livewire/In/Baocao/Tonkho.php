<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-07-16 13:56:12
 */

namespace Diepxuan\Catalog\Http\Livewire\In\Baocao;

use Diepxuan\Catalog\Models\InDmVt;
use Diepxuan\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class Tonkho extends Component
{
    public $pMa_vt;
    public $pMa_kho;
    public $pMa_vitri;
    public $pTk_vt;
    public $pMa_nhvt;
    public $pNgay;
    public $pDVT      = 0;
    public $pNgoai_te = 'VND';
    public $pDk_Ck    = true;
    public $pMa_lo;
    public $pQuaToiThieu = 0;
    public $pQuaToiDa    = 0;
    protected $inDmVts;

    public function mount(): void
    {
        $this->pMa_Nt = \CatalogService::ma_Nt();
        $this->pNgay  = now()->toDateString();
    }

    public function updated($property): void
    {
        // \Debugbar::info($property);
    }

    public function submit(): void
    {
        $this->inDmVts = InDmVt::getAsINRptCD02([
            'pMa_Cty'      => \CatalogService::company()->id,
            'pMa_vt'       => $this->pMa_vt ?? '',
            'pMa_kho'      => $this->pMa_kho ?? '',
            'pMa_vitri'    => $this->pMa_vitri ?? '',
            'pTk_vt'       => $this->pTk_vt ?? '',
            'pMa_nhvt'     => $this->pMa_nhvt ?? '',
            'pNgay'        => $this->pNgay ?? '',
            'pDVT'         => $this->pDVT ?? '0',
            'pNgoai_te'    => $this->pNgoai_te,
            'pDk_Ck'       => $this->pDk_Ck ?? 1,
            'pMa_lo'       => $this->pMa_lo ?? '',
            'pQuaToiThieu' => $this->pQuaToiThieu ?? 0,
            'pQuaToiDa'    => $this->pQuaToiDa ?? 0,
        ]);
        // ->take(5)->dump()
    }

    /**
     * Render the component.
     *
     * @return View
     */
    public function render()
    {
        $this->inDmVts ??= collect([]);

        $this->inDmVts = $this->inDmVts instanceof Collection
            ? $this->inDmVts
            : Collection::make($this->inDmVts->all());

        // diepxuan/laravel-catalog/resources/views/in/baocao/tonkho.blade.php
        return view('catalog::in.baocao.tonkho', [
            'inDmVts' => $this->inDmVts->toMarkdownTable([
                'ma_vt'    => 'Mã vật tư',
                'ten_vt'   => 'Tên vật tư',
                'dvt'      => 'ĐVT',
                'so_luong' => 'Số lượng',
                'tien_fm'  => 'Giá trị',
                'vat_tu'   => 'Vật tư',
                // 'ma_nhvt'      => 'Mã Nhóm',
                // 'ma_kho'       => 'Mã Kho',
                // 'ma_vitri'     => 'Mã vị trí',
                // 'tk_vt'        => 'TK VT',
                // 'tk_dt'        => 'TK DT',
                // 'tk_dtnb'      => 'TK DTNB',
                // 'tk_gv'        => 'TK GV',
                // 'tk_tl'        => 'TK TL',
                // 'tk_ck'        => 'TK CK',
                // 'ton_kho'      => 'Tính tồn kho',
                // 'loai'         => 'Loại',
                // 'ten_loai'     => 'Tên loại',
                // 'gia_ton'      => 'Giá tồn',
                // 'ten_loai_gia' => 'Tên loại giá',
                // 'tinh_gt'      => 'Tính giá thành',
                // 'ma_nhvat'     => 'Mã nhóm VAT',
                // 'nuoc_sx'      => 'Nước sản xuất',
                // 'ma_plvt1'     => 'Mã PL VT 1',
                // 'ma_plvt2'     => 'Mã PL VT 2',
                // 'ma_plvt3'     => 'Mã PL VT 3',
                // 'dvt_ban'      => 'ĐVT bán',
                // 'hs_dvtban'    => 'HS ĐVT bán',
                // 'dvt_mua'      => 'ĐVT mua',
                // 'hs_dvtmua'    => 'HS ĐVT mua',
                // 'ma_thue'      => 'Mã thuế',
                // 'sl_min'       => 'SL min',
                // 'sl_max'       => 'SL max',
                // 'nha_sx'       => 'Nhà SX',
                // 'nha_pp'       => 'Nhà PP',
                // 'ksd'          => 'KSD',
                // 'cdate'        => 'Ngày tạo',
                // 'cuser'        => 'Người tạo',
                // 'ldate'        => 'Ngày sửa',
                // 'luser'        => 'Người sửa',
            ], true),
            // 'inDmVts' => $this->inDmVts,
        ])->layout('catalog::layouts.app');
    }
}
