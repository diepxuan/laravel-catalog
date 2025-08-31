<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-08-24 21:35:56
 */

namespace Diepxuan\Catalog\Http\Livewire\System\Menu;

use Diepxuan\Catalog\Models\NavigationMenu;
use Illuminate\View\View;
use Livewire\Component;

class Item extends Component
{
    public NavigationMenu $menu;
    public array $childIds = [];
    protected $menus;

    // public function mount(): void
    // {
    //     // \Debugbar::info($this->timer);
    //     // $this->menus = NavigationMenu::getTree();
    // }

    public function mount($menuId): void
    {
        $this->menus = \CatalogService::menus();
        $this->menu  = $this->menus->where('id', $menuId)->first()
            ?? NavigationMenu::findOrFail($menuId);
        $this->loadChildren();
    }

    public function loadChildren(): void
    {
        $this->childIds = $this->menus->where('parent_id', $this->menu->id)->pluck('id')->toArray();
    }

    public function deleteMenu(): void
    {
        // NavigationMenu::findOrFail($id)->delete();
        $this->menu->delete();
        // $this->dispatchUp('menuDeleted');
        $this->dispatch('menuDeleted');
    }

    /**
     * Render the component.
     *
     * @return View
     */
    public function render()
    {
        // diepxuan/laravel-catalog/resources/views/system/menu/item.blade.php
        return view('catalog::system.menu.item', [
            'menu'     => $this->menu,
            'childIds' => $this->childIds,
        ])->layout('catalog::layouts.app');
    }
}
