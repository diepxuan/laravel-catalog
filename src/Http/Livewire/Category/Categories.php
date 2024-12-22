<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-12-22 12:47:05
 */

namespace Diepxuan\Catalog\Http\Livewire\Category;

use Diepxuan\Catalog\Models\Category;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Laravel\Jetstream\Http\Livewire\NavigationMenu as NavigationMenuComponent;
use Livewire\Component;

class Categories extends NavigationMenuComponent
{
    public ?string $id = null;
    public $category;
    public $childrens;
    public $template;

    public function mount(?string $id = null): void
    {
        $this->id ??= Str::of($id)->toString();
        $this->category ??=  Category::find($this->id) ?? Category::isRoot()->get()->first();
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
