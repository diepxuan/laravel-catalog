<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-06-19 10:42:50
 */

namespace Diepxuan\Catalog\Http\Controllers;

use App\Http\Controllers\Controller;
use Diepxuan\Catalog\Models\Product;
use Diepxuan\Simba\Models\Product as SProduct;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('catalog::product/index', [
            'products' => Product::all(),
            // 'products' => SProduct::withQuantity()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('catalog::product/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        return Redirect::route('catalog.index');
    }

    /**
     * Show the specified resource.
     *
     * @param mixed $sanpham
     */
    public function show(Product $sanpham)
    {
        return view('catalog::product/show', [
            'product' => $sanpham,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $sanpham)
    {
        return view('catalog::product/edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param mixed $request
     * @param mixed $sanpham
     */
    public function update(Request $request, Product $sanpham): RedirectResponse
    {
        $sanpham->sku      = $request->get('sku', $sanpham->sku);
        $sanpham->name     = $request->get('name', $sanpham->name);
        $sanpham->price    = (float) str_replace(',', '', $request->get('price', $sanpham->price));
        $sanpham->category = $request->get('category', $sanpham->category);
        $sanpham->quantity = $request->get('quantity', (float) ($sanpham->quantity ?: 0));
        $sanpham->status   = $request->boolean('status');
        $sanpham->save();

        if ($request->exists('productedit')) {
            return Redirect::route('catalog.show', $sanpham->id);
        }

        return Redirect::route('catalog.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param mixed $id
     */
    public function destroy($id): void {}
}
