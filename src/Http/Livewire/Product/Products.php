<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-12-24 21:44:07
 */

namespace Diepxuan\Catalog\Http\Livewire\Product;

use Illuminate\View\View;
use Laravel\Jetstream\Http\Livewire\NavigationMenu as NavigationMenuComponent;
use Livewire\Component;

class Products extends NavigationMenuComponent
{
    public $product;
    public $price;

    public function mount(): void
    {
        if ($this->product) {
            \Debugbar::info($this->product);

            // $this->price ??= $this->product->price . ' VND';
            $formatter = new \NumberFormatter('vi_VN', \NumberFormatter::CURRENCY);
            $this->price ??=  $formatter->formatCurrency((float) $this->product->price, 'VND');
        }
    }

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
