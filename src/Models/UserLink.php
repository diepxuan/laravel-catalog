<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-05-21 17:11:17
 */

namespace Diepxuan\Catalog\Models;

use App\Models\User;
use Diepxuan\Simba\Models\SysUserInfo;

class UserLink extends AbstractModel
{
    protected $autoCreateTable = true;

    protected $fillable = ['laravel_user_id', 'simba_user_id'];

    public function laravelUser()
    {
        return $this->belongsTo(User::class, 'laravel_user_id');
    }

    public function simbaUser()
    {
        return $this->belongsTo(SysUserInfo::class, 'simba_user_id', 'username');
    }
}
