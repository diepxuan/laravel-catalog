<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-06-16 10:00:00
 */

namespace Diepxuan\Catalog\Http\Livewire;

use Illuminate\View\View;
use Laravel\Jetstream\Http\Livewire\NavigationMenu as NavigationMenuComponent;

class NavigationMenu extends NavigationMenuComponent
{
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
     */
    public function render(): View
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
    public function isActive(...$routes): bool
    {
        return collect($routes)
            ->flatten()
            ->map(static fn ($route) => request()->routeIs($route) || request()->routeIs("{$route}.*"))
            ->filter(static fn ($flag) => $flag)
            ->isNotEmpty()
        ;
    }
}
