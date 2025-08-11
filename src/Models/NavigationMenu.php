<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-08-11 23:11:30
 */

namespace Diepxuan\Catalog\Models;

use Illuminate\Database\Eloquent\Model;

class NavigationMenu extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'menus';

    public function items()
    {
        return $this->hasMany(self::class)
            ->where('parent_id', $this->id)
            ->orderBy('order')
        ;
    }
}
