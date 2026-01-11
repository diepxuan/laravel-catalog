<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-01-11 12:31:54
 */

namespace Diepxuan\Catalog\Http\Livewire\System\Menu;

use Diepxuan\Catalog\Models\NavigationMenu;
use Illuminate\View\View;
use Livewire\Component;

class Item extends Component
{
    public NavigationMenu $menu;
    public array $childIds = [];
    public $parentId;

    public function mount($menuId): void
    {
        $this->menu = \CatalogService::menus()->where('id', $menuId)->first()
            ?? NavigationMenu::findOrFail($menuId);
        $this->parentId = $this->menu->parent_id;
        $this->loadChildren();
    }

    public function loadChildren(): void
    {
        $this->childIds = \CatalogService::menus()->where('parent_id', $this->menu->id)->pluck('id')->toArray();
    }

    public function deleteMenu(): void
    {
        // NavigationMenu::findOrFail($id)->delete();
        $this->menu->delete();
        // $this->dispatchUp('menuDeleted');
        $this->dispatch('menuDeleted');
    }

    public function updateMenu(): void
    {
        // \Debugbar::info($this->parentId);
        // \Debugbar::info($this->menu);
        if ($this->menu->id === $this->parentId) {
            return;
        }
        $this->menu->parent_id = $this->parentId ?: null;
        $this->menu->save();
        $this->dispatch('menuUpdated');
    }

    /**
     * Render the component.
     *
     * @return View
     */
    public function render()
    {
        // Lấy danh sách menu (trừ chính nó và children) để làm parent
        $allMenus = \CatalogService::menus()->whereNotIn('id', [$this->menu->id, ...$this->childIds]);
        $availableParents = $this->buildMenuTree($allMenus->where('parent_id', null)->values());

        // diepxuan/laravel-catalog/resources/views/system/menu/item.blade.php
        return view('catalog::system.menu.item', [
            'menu'             => $this->menu,
            'childIds'         => $this->childIds,
            'availableParents' => $availableParents,
        ])->layout('catalog::layouts.app');
    }

    /**
     * Xây dựng cấu trúc cây menu từ danh sách menu.
     *
     * @param mixed $menus
     */
    private function buildMenuTree($menus): array
    {
        $allMenus = \CatalogService::menus()->whereNotIn('id', [$this->menu->id, ...$this->childIds]);
        $result = [];

        foreach ($menus as $menu) {
            $result[] = [
                'id'       => $menu->id,
                'name'     => $menu->name,
                'route'    => $menu->route,
                'display'  => $menu->name . ($menu->route ? " ({$menu->route})" : ''),
                'children' => $this->buildMenuTree($allMenus->where('parent_id', $menu->id)->values()),
            ];
        }

        return $result;
    }
}
