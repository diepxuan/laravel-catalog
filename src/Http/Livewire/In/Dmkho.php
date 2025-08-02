<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-08-02 21:43:52
 */

namespace Diepxuan\Catalog\Http\Livewire\In;

use Diepxuan\Catalog\Models\InDmKho;
use Diepxuan\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class Dmkho extends Component
{
    public $pMa_kho;
    public $pStruct;
    protected $inDmKhos;

    public function mount(): void
    {
        $collection = InDmKho::getAsINGetDMKHO([
            'pMa_Cty' => \CatalogService::company()->id,
            'pMa_kho' => $this->pMa_kho ?? null,
            'pStruct' => $this->pStruct ?? null,
        ]);

        $this->inDmKhos = $collection instanceof Collection
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
        return view('catalog::in.dmkho', [
            'inDmKhos' => $this->inDmKhos->toMarkdownTable([
                'ma_kho'   => 'Mã kho',
                'ten_kho'  => 'Tên kho',
                'kho_dl'   => 'Kho đại lý',
                'tk_dl'    => 'TK đại lý',
                'dia_chi'  => 'Địa chỉ',
                'nguoi_lh' => 'Người liên hệ',
                'tel'      => 'Số điện thoại',
                'fax'      => 'Số fax',
                'email'    => 'Email',
                'stt_ntxt' => 'Số thứ tự NTXT',
                'ksd'      => 'KSD',
                'cdate'    => 'Ngày tạo',
                'cuser'    => 'Người tạo',
                'ldate'    => 'Ngày sửa',
                'luser'    => 'Người sửa',
            ], true),
            // 'inDmVts' => $this->inDmVts,
        ])->layout('catalog::layouts.app');
    }
}
