<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-05-14 16:48:24
 */

namespace Diepxuan\Catalog\Http\Controllers;

use App\Http\Controllers\Controller;
use Diepxuan\Catalog\Models\Category;
use Diepxuan\Magento\Magento;
use Diepxuan\Simba\Models\Category as sCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('catalog::category.index', [
            'categories'  => Category::all(),
            'sCategories' => sCategory::all(),
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
        $sku     = $request->get('sku');
        $name    = $request->get('name');
        $price   = $request->get('price', 0);
        $url_key = Str::of(vn_convert_encoding($name))->lower()->replace(' ', '-');

        Magento::products()->create([
            'sku'               => $sku,
            'name'              => $name,
            'price'             => $price,
            'attribute_set_id'  => 4,
            'status'            => 1,
            'visibility'        => 4,
            'type_id'           => 'simple',
            'custom_attributes' => [
                [
                    'attribute_code' => 'meta_title',
                    'value'          => $name,
                ],
                [
                    'attribute_code' => 'meta_keyword',
                    'value'          => $name,
                ],
                [
                    'attribute_code' => 'meta_description',
                    'value'          => $name,
                ],
                [
                    'attribute_code' => 'url_key',
                    'value'          => $url_key,
                ],
            ],
        ]);

        return Redirect::route('catalog.category');
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
     *
     * @param mixed $id
     */
    public function update(Request $request, $id): RedirectResponse {}

    /**
     * Remove the specified resource from storage.
     *
     * @param mixed $id
     */
    public function destroy($id): void {}
}