<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-08-12 23:07:23
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
    public array $rootIds = [];

    protected $listeners = [
        'menuDeleted' => 'refreshTree',
    ];

    public function mount(): void
    {
        $this->refreshTree();
    }

    public function refreshTree(): void
    {
        $this->rootIds = NavigationMenu::isParent()->get()->pluck('id')->toArray();
    }

    public function addMenu(): void
    {
        NavigationMenu::create($this->newMenu);
        $this->reset('newMenu');
        $this->refreshTree();
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
        ])->layout('catalog::layouts.app');
    }
}
