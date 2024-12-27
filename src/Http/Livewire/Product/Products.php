<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-12-27 12:34:45
 */

namespace Diepxuan\Catalog\Http\Livewire\Product;

use Diepxuan\Catalog\Models\Product;
use Illuminate\View\View;
use Livewire\Component;

class Products extends Component
{
    public $products;
    public $perPage      = 50;
    public $hasMore      = true;
    protected $listeners = ['loadMoreProducts'];

    public function loadMoreProducts(): void
    {
        $this->loadProducts();
    }

    public function mount(): void
    {
        $this->products = Product::query()
            ->take($this->perPage)
            ->get()
        ;
        $this->hasMore = $this->products->count() >= $this->perPage;
    }

    public function loadProducts(): void
    {
        if (!$this->hasMore) {
            return;
        }

        $newProducts = Product::query()
            ->skip(\count($this->products ?? collect()))
            ->take($this->perPage)
            ->get()
        ;

        $this->products = $this->products->merge($newProducts);
        $this->hasMore  = $newProducts->count() >= $this->perPage;
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
