<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-05-21 17:47:59
 */

namespace Diepxuan\Catalog\View\Components;

use Diepxuan\Catalog\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Illuminate\View\View;

// <x-catalog::sys-user-info>
class SysUserInfo extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): string|View
    {
        $user      = Auth::user();
        $simbaUser = $user->getSimbaUser();

        if (null === $simbaUser) {
            return '';
        }

        return <<<HTML
                <span>{$simbaUser->username}</span>
            HTML;
    }
}
