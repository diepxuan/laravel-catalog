<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-05-20 16:05:18
 */

namespace Diepxuan\Catalog\Http\Controllers;

use App\Http\Controllers\Controller;
use Diepxuan\Simba\Models\System;
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
     */
    public function store(Request $request): RedirectResponse
    {
        return Redirect::route('system.index');
    }

    /**
     * Show the specified resource.
     *
     * @param mixed $id
     */
    public function show($id)
    {
        return view('catalog::system.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param mixed $id
     */
    public function edit($id)
    {
        return view('catalog::system.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, System $system): RedirectResponse
    {
        $system->khoaSo = $request->get('khoaso');
        // $system->save();
        // dd($system);

        return Redirect::route('system.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param mixed $id
     */
    public function destroy($id): void {}
}
