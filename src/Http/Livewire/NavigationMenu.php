<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-01-11 15:56:01
 */

namespace Diepxuan\Catalog\Http\Livewire;

use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
use Laravel\Jetstream\Http\Livewire\NavigationMenu as NavigationMenuComponent;
use Livewire\Component;

class NavigationMenu extends NavigationMenuComponent
{
    public $menus;

    /**
     * The component's listeners.
     *
     * @var array
     */
    protected $listeners = [
        'refresh-navigation-menu' => '$refresh',
    ];

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->menus = \CatalogService::menuTree();
    }

    /**
     * Render the component.
     *
     * @return View
     */
    public function render()
    {
        return view('catalog::navigation-menu');
    }

    /**
     * Check if the given route is active.
     *
     * @param mixed ...$routes
     *
     * @return bool
     */
    public function isActive(...$routes)
    {
        return collect($routes)
            ->flatten()
            ->map(static fn ($route) => request()->routeIs($route) || request()->routeIs("{$route}.*"))
            ->filter(static fn ($flag) => $flag)
            ->isNotEmpty()
        ;
    }
}
