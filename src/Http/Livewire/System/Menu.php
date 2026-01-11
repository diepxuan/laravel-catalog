<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-01-11 00:20:53
 */

namespace Diepxuan\Catalog\Http\Livewire\System;

use Diepxuan\Catalog\Models\NavigationMenu;
use Illuminate\View\View;
use Livewire\Component;

class Menu extends Component
{
    public array $newMenu = [
        'parent_id' => null,
        'name'      => '',
        'route'     => '',
    ];
    protected array $rootIds = [];
    protected $menus;

    protected $listeners = [
        'menuDeleted' => 'menuDeleted',
        'menuUpdated' => 'menuUpdated',
    ];

    public function mount(): void
    {
        $this->refreshTree();
    }

    public function refreshTree(bool $forceReload = false): void
    {
        $this->menus = \CatalogService::menus($forceReload);
        // $this->rootIds = NavigationMenu::isParent()->get()->pluck('id')->toArray();
        $this->rootIds = \CatalogService::menuTree()->pluck('id')
            ->toArray()
        ;
        // dd($this->rootIds, $this->menus->pluck('id')->toArray());
    }

    public function addMenu(): void
    {
        NavigationMenu::create($this->newMenu);
        $this->reset('newMenu');
        $this->refreshTree(true);
    }

    public function menuDeleted(): void
    {
        $this->refreshTree(true);
    }

    public function menuUpdated(): void
    {
        $this->refreshTree(true);
    }

    public function updateMenu($id, $parentId, $preId): void
    {
        // \Debugbar::info($id, $parentId, $preId);

        $menu = \CatalogService::menus()->where('id', $id)->first()
            ?? NavigationMenu::findOrFail($id);

        if (!$menu) {
            $this->refreshTree();

            return;
        }

        if (null === $preId) {
            $order = 0;
        } else {
            $preMenu = \CatalogService::menus()->where('id', $preId)->first()
                ?? NavigationMenu::findOrFail($preId);
            $order = $preMenu->order + 0.5;
        }

        // \Debugbar::info($id, $menu, $parentId, $preId, $order);

        // $menu            = NavigationMenu::findOrFail($id);
        $menu->parent_id = $parentId;
        $menu->order     = $order;
        $menu->save();

        // NavigationMenu::findOrFail($id)->delete();
        // $this->menu->delete();
        // $this->dispatchUp('menuDeleted');
        // $this->dispatch('menuDeleted');
        $this->refreshTree($forceReload = true);
        // $this->refreshTree();
    }

    public function updateOrder($draggedId, $targetId, $position): void
    {
        // Ví dụ: position = 'top' hoặc 'bottom'
        $dragged = NavigationMenu::findOrFail($draggedId);
        $target  = NavigationMenu::findOrFail($targetId);

        if ('top' === $position) {
            $newOrder = $target->order - 1;
        } else {
            $newOrder = $target->order + 1;
        }

        $dragged->order = $newOrder;
        $dragged->save();

        // Reload menus
        $this->menus = NavigationMenu::orderBy('order')->get();
    }

    /**
     * Render the component.
     *
     * @return View
     */
    public function render()
    {
        // diepxuan/laravel-catalog/resources/views/system/menu.blade.php
        return view('catalog::system.menu', [
            'rootIds' => $this->rootIds,
            'menus'   => $this->menus,
        ])->layout('catalog::layouts.app');
    }
}
