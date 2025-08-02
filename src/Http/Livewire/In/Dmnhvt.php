<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-08-02 21:39:58
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
        // \Debugbar::info([
        //     'pMa_Cty'  => \CatalogService::company()->id,
        //     'pMa_nhvt' => $this->pMa_nhvt ?? null,
        //     'pStruct'  => $this->pStruct ?? null,
        // ]);

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
            'inDmNhvts' => $this->inDmNhvts->toMarkdownTable([
                'ma_nhvt'       => 'Mã nhóm vật tư',
                'ten_nhvt_view' => 'Tên nhóm vật tư',
                'nhom_me'       => 'Nhóm mẹ',
                'cong_sl'       => 'Cộng số lượng',
                'tk_vt'         => 'TK vật tư',
                'tk_dt'         => 'TK doanh thu',
                'tk_dtnb'       => 'TK doanh thu nội bộ',
                'tk_gv'         => 'TK giá vốn',
                'tk_tl'         => 'TK trả lại',
                'tk_ck'         => 'TK chiết khấu',
                'ksd'           => 'KSD',
                'cdate'         => 'Ngày tạo',
                'cuser'         => 'Người tạo',
                'ldate'         => 'Ngày sửa',
                'luser'         => 'Người sửa',
            ], true),
            // 'inDmVts' => $this->inDmVts,
        ])->layout('catalog::layouts.app');
    }
}
