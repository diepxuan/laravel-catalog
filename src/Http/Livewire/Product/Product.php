<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-12-27 12:34:03
 */

namespace Diepxuan\Catalog\Http\Livewire\Product;

use Illuminate\View\View;
use Livewire\Component;

class Product extends Component
{
    public $product;

    /**
     * Render the component.
     *
     * @return View
     */
    public function render()
    {
        return view('catalog::product.product');
    }
}
