<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-05-31 09:32:17
 */

namespace Diepxuan\Catalog\Models;

use Diepxuan\Simba\Models\SysUserInfo as Model;

class SysUserInfo extends Model
{
    public function companies()
    {
        return $this->belongsToMany(
            SysCompany::class,
            'sysUserCompanyRight',
            'Username',  // Foreign key on pivot (trỏ tới user)
            'Ma_cty',    // Foreign key on pivot (trỏ tới company)
            'username',  // Local key on this model
            'ma_cty'     // Local key on SysCompany
        );
    }
}
