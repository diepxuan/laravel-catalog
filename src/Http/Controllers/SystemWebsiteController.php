<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-06-14 18:56:18
 */

namespace Diepxuan\Catalog\Http\Controllers;

use App\Http\Controllers\Controller;
use Diepxuan\Magento\Magento;
use Diepxuan\Simba\Models\System;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SystemWebsiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('catalog::system.website.index', [
            'websites' => Magento::store_websites()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('catalog::system.website.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param mixed $request
     */
    public function store(Request $request): RedirectResponse
    {
        return Redirect::route('systemwebsite.index');
    }

    /**
     * Show the specified resource.
     *
     * @param mixed $hethong
     */
    public function show(System $hethong)
    {
        return view('catalog::system.website.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param mixed $hethong
     */
    public function edit(System $hethong)
    {
        return view('catalog::system.website.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param mixed $request
     */
    public function update(Request $request): RedirectResponse
    {
        return Redirect::route('systemwebsite.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param mixed $hethong
     */
    public function destroy(System $hethong): RedirectResponse
    {
        return Redirect::route('systemwebsite.index');
    }
}
