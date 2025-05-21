<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-05-21 17:47:23
 */

namespace Diepxuan\Catalog\Models;

use App\Models\User as Model;
use Diepxuan\Simba\Models\SysUserInfo as SUser;

class User extends Model
{
    public function simbaLink()
    {
        return $this->hasOne(UserLink::class, 'laravel_user_id');
    }

    public function getSimbaUser()
    {
        if (null === $this->simbaLink) {
            return null;
        }
        if (null === $this->simbaLink->simba_user_id) {
            return null;
        }

        return SUser::get($this->simbaLink->simba_user_id);
    }
}
