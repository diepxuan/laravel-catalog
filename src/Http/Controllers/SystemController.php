<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-06-21 07:59:02
 */

namespace Diepxuan\Catalog\Http\Controllers;

use App\Http\Controllers\Controller;
use Diepxuan\Catalog\Models\System;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SystemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('catalog::system.index', [
            'system' => System::first(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('catalog::system.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param mixed $request
     */
    public function store(Request $request): RedirectResponse
    {
        return Redirect::route('system.index');
    }

    /**
     * Show the specified resource.
     *
     * @param mixed $hethong
     */
    public function show(System $hethong)
    {
        return view('catalog::system.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param mixed $hethong
     */
    public function edit(System $hethong)
    {
        return view('catalog::system.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param mixed $request
     * @param mixed $id
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $system         = System::find($id);
        $system->khoaSo = $request->get('khoaso');

        return Redirect::route('system.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param mixed $hethong
     */
    public function destroy(System $hethong): void {}
}
