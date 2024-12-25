<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-12-25 11:23:42
 */

namespace Diepxuan\Catalog\Http\Livewire\Product;

use Illuminate\View\View;
use Laravel\Jetstream\Http\Livewire\NavigationMenu as NavigationMenuComponent;
use Livewire\Component;

class Products extends NavigationMenuComponent
{
    public $product;

    /**
     * Render the component.
     *
     * @return View
     */
    public function render()
    {
        return view('catalog::product.products');
    }
}
