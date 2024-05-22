<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-05-22 12:46:39
 */

namespace Diepxuan\Catalog\Http\Controllers;

use App\Http\Controllers\Controller;
use Diepxuan\Catalog\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('catalog::category.index', [
            'categories' => Category::isParent()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('catalog::category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        return Redirect::route('category.index');
    }

    /**
     * Show the specified resource.
     *
     * @param mixed $id
     */
    public function show($id)
    {
        return view('catalog::category.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param mixed $id
     */
    public function edit($id)
    {
        return view('catalog::category.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $category->sku             = $request->get('sku', $category->sku);
        $category->parent          = $request->get('parent', $category->parent);
        $category->name            = $request->get('name', $category->name);
        $category->urlKey          = $request->get('urlKey', $category->urlKey);
        $category->simba_id        = $request->get('simba_id', $category->simba_id);
        $category->magento_id      = $request->get('magento_id', $category->magento_id);
        $category->include_in_menu = $request->boolean('include_in_menu');
        $category->save();

        return Redirect::route('category.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param mixed $id
     */
    public function destroy($id): void {}
}
