<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-12-27 12:35:10
 */

namespace Diepxuan\Catalog\Http\Livewire\Category;

use Diepxuan\Catalog\Models\Category;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;

class Categories extends Component
{
    public ?string $id = null;
    public $category;
    public $childrens;
    public $template;
    public $magento;
    public $enabled;

    public function loadData(): void
    {
        $this->id ??= $this->category->sku;
        $this->magento = $this->category->magento;
        $this->magento->each(function ($m2child): void {
            $this->enabled = $this->enabled || $m2child->is_active;
            \Debugbar::info($m2child);
        });
    }

    public function toggleEnabled(): void
    {
        $this->enabled = !$this->enabled;
        \Debugbar::info($this->id, $this->enabled);
    }

    public function mount(?string $id = null): void
    {
        $this->id ??= Str::of($id)->toString();
        $this->category ??=  Category::find($this->id) ?? Category::isRoot()->get()->first();
        $this->id = $this->category->sku;
        $this->magento ??= collect([]);
        $this->childrens ??= $this->category->catChildrens()->get();
        $this->template = $this->childrens->isNotEmpty() ? 'catalog::category.categories' : 'catalog::category.category';
    }

    /**
     * Render the component.
     *
     * @return View
     */
    public function render()
    {
        return view('catalog::category.categories');
    }
}
