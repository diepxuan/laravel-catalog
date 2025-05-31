<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-05-31 09:32:58
 */

namespace Diepxuan\Catalog\Models;

use App\Models\User as Model;

class User extends Model
{
    public function simbaLink()
    {
        return $this->hasOne(UserLink::class, 'laravel_user_id');
    }

    public function getSimbaUser()
    {
        return SysUserInfo::find(optional($this->simbaLink)->simba_user_id);
    }
}
