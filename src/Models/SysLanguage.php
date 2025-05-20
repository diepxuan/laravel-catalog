<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-05-20 10:27:33
 */

namespace Diepxuan\Catalog\Models;

use Diepxuan\Simba\Models\SysLanguage as SsysLanguage;

class SysLanguage extends SsysLanguage
{
    /**
     * is Enable scope.
     *
     * @param mixed $query
     */
    public function scopeIsEnable($query)
    {
        return $query->where('ksd', 0);
    }

    /**
     * Current scope.
     *
     * @param mixed $query
     */
    public function scopeCurrent($query)
    {
        return $query->where('Selected', 1);
    }
}
