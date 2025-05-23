<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-05-23 16:04:41
 */

namespace Diepxuan\Catalog\Http\Controllers;

use App\Http\Controllers\Controller;
use Diepxuan\Catalog\Models\SysUserInfo;
use Diepxuan\Catalog\Models\User;
use Diepxuan\Simba\Models\System;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SystemUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $sysUsers = SysUserInfo::all();
        $users    = User::all();
        // $options  = $users->mapWithKeys(static fn ($user) => [$user->id => "{$user->name} ({$user->email})"])->toArray();

        // diepxuan/laravel-catalog/resources/views/system/user/index.blade.php
        return view('catalog::system.user.index', [
            'sysUsers' => $sysUsers,
            'users'    => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('catalog::system.user.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param mixed $request
     */
    public function store(Request $request): RedirectResponse
    {
        return Redirect::route('system.user.index');
    }

    /**
     * Show the specified resource.
     *
     * @param mixed $hethong
     */
    public function show(System $hethong)
    {
        return view('catalog::system.user.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param mixed $hethong
     */
    public function edit(System $hethong)
    {
        return view('catalog::system.user.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param mixed $request
     */
    public function update(Request $request): RedirectResponse
    {
        return Redirect::route('system.user.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param mixed $hethong
     */
    public function destroy(System $hethong): RedirectResponse
    {
        return Redirect::route('system.user.index');
    }
}
