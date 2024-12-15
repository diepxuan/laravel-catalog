<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-12-13 22:03:52
 */

namespace Diepxuan\Catalog\Http\Livewire;

use Illuminate\View\View;
use Laravel\Jetstream\Http\Livewire\NavigationMenu as NavigationMenuComponent;
use Livewire\Component;

class NavigationMenu extends NavigationMenuComponent
{
    public $menus = [
        [
            'name'   => 'Bán hàng',
            'status' => 'sell.*',
            'items'  => [
                'Hoá đơn bán hàng' => 'sell.index',
                'space',
                // 'Danh sách hàng hoá vật tư' => 'catalog.index',
                // 'Nhóm hàng hoá vật tư' => 'category.index',
            ],
        ],
        [
            'name'   => 'Mua hàng',
            'status' => 'sell.*',
            'items'  => [
                // 'Hoá đơn mua hàng' => 'purchase.index',
                'space',
                // 'Danh sách hàng hoá vật tư' => 'catalog.index',
                // 'Nhóm hàng hoá vật tư' => 'category.index',
            ],
        ],
        [
            'name'   => 'Hàng tồn kho',
            'status' => 'catalog.*',
            'items'  => [
                'Phiếu xuất điều chuyển kho' => 'catalog.inventory.index',
                'space',
                'Danh sách hàng hoá vật tư' => 'catalog.index',
                'Nhóm hàng hoá vật tư'      => 'catalog.category.index',
            ],
        ],
        [
            'name'   => 'Hệ Thống',
            'status' => 'system.*',
            'items'  => [
                'Dashboard' => 'system.index',
                'Website'   => 'system.website.index',
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
