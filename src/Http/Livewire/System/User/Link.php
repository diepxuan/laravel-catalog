<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-05-23 16:32:30
 */

namespace Diepxuan\Catalog\Http\Livewire\System\User;

use Diepxuan\Catalog\Models\System;
use Illuminate\View\View;
use Livewire\Component;

class Link extends Component
{
    public $sysUser;
    public $users;
    public $options;
    public $userId;

    public function loadData(): void {}

    public function mount(): void
    {
        // \Debugbar::info($this->sysUser);
        // \Debugbar::info($this->users);

        $this->options ??= $this->users->mapWithKeys(static fn ($user) => [$user->id => "{$user->name} ({$user->email})"])->toArray();
        $this->userId = $this->users
            ->firstWhere(fn ($user) => $this->sysUser->username === optional($user->simbalink)->simba_user_id)
            ?->id
        ;
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateUserLink(): void
    {
        $user = $this->users->where('id', $this->userId)->first();
        // \Debugbar::info($user, $this->sysUser);

        $user->simbaLink()->updateOrCreate(
            ['laravel_user_id' => $user->id],
            ['simba_user_id' => $this->sysUser->username]
        );
    }

    /**
     * Render the component.
     *
     * @return View
     */
    public function render()
    {
        // diepxuan/laravel-catalog/resources/views/system/user/link.blade.php
        return view('catalog::system.user.link');
    }
}
