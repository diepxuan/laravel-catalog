<?php

declare(strict_types=1);

/*
 * Copyright © 2019 Dxvn, Inc. All rights reserved.
 *
 * © Tran Ngoc Duc <ductn@diepxuan.com>
 *   Tran Ngoc Duc <caothu91@gmail.com>
 */

namespace Modules\Catalog\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('catalog::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('catalog::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse {}

    /**
     * Show the specified resource.
     *
     * @param mixed $id
     */
    public function show($id)
    {
        return view('catalog::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param mixed $id
     */
    public function edit($id)
    {
        return view('catalog::edit');
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
