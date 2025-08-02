<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-08-02 21:37:50
 */

namespace Diepxuan\Catalog\Http\Livewire;

use Illuminate\View\View;
use Laravel\Jetstream\Http\Livewire\NavigationMenu as NavigationMenuComponent;
use Livewire\Component;

class NavigationMenu extends NavigationMenuComponent
{
    public $menus = [
        [
            'name'   => 'Tổng hợp',
            'status' => 'gl.*',
            'items'  => [
                'Cấu hình'  => 'space',
                'Tài khoản' => 'gl.taikhoan',
            ],
        ],
        [
            'name'   => 'Tiền tệ',
            'status' => 'ca.*',
            'items'  => [
                'Tiền mặt'         => 'space',
                'Phiếu thu'        => 'ca.tienmat.thu',
                'Phiếu chi'        => 'ca.tienmat.chi',
                'Chuyển khoản'     => 'space',
                'Báo có'           => 'ca.nganhang.baoco',
                'Báo nợ'           => 'ca.nganhang.baono',
                'Báo cáo'          => 'space',
                'Sổ quỹ tiền mặt'  => 'ca.tienmat.quy',
                'Sổ quỹ ngân hàng' => 'ca.nganhang.quy',
                'Nhật ký thu tiền' => 'ca.thu',
                'Nhật ký chi tiền' => 'ca.chi',
                'Nhật ký thu chi'  => 'ca.quy',
            ],
        ],
        [
            'name'   => 'Bán hàng',
            'status' => 'ar.*',
            'items'  => [
                'Hoá đơn bán hàng'     => 'ar.ph.hdbh',
                'Cấu hình'             => 'space',
                'Danh sách khách hàng' => 'ar.khachhang',
                // 'Danh sách hàng hoá vật tư' => 'catalog.index',
                // 'Nhóm hàng hoá vật tư' => 'category.index',
            ],
        ],
        [
            'name'   => 'Mua hàng',
            'status' => 'purchase.*',
            'items'  => [
                // 'Hoá đơn mua hàng' => 'purchase.index',
                'space',
                // 'Danh sách hàng hoá vật tư' => 'catalog.index',
                // 'Nhóm hàng hoá vật tư' => 'category.index',
            ],
        ],
        [
            'name'   => 'Hàng tồn kho',
            'status' => 'in.*',
            'items'  => [
                // 'Phiếu xuất điều chuyển kho' => 'catalog.inventory.index',
                'Cấu hình'                  => 'space',
                'Danh sách hàng hoá vật tư' => 'in.dmvt',
                'Nhóm hàng hoá vật tư'      => 'in.dmnhvt',
                'Kho hàng'                  => 'in.khohang',
                'Báo cáo'                   => 'space',
                'Báo cáo tồn kho'           => 'in.tonkho',
            ],
        ],
        [
            'name'   => 'Hệ Thống',
            'status' => 'system.*',
            'items'  => [
                'Dashboard'          => 'system.index',
                'Quản lý người dùng' => 'system.user.index',
                'Website'            => 'system.website.index',
            ],
        ],
    ];

    /**
     * The component's listeners.
     *
     * @var array
     */
    protected $listeners = [
        'refresh-navigation-menu' => '$refresh',
    ];

    /**
     * Render the component.
     *
     * @return View
     */
    public function render()
    {
        return view('catalog::navigation-menu');
    }

    public function status(...$args)
    {
        return collect($args)
            ->flatten()
            ->map(static fn ($route) => request()->routeIs($route))
            ->filter(static fn ($flag) => $flag)
            ->isNotEmpty()
        ;
    }
}
