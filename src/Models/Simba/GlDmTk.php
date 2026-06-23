<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-05-30 11:51:26
 */

namespace Diepxuan\Catalog\Models\Simba;

use Diepxuan\Simba\Models\GlDmTk as SimbaModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;

class GlDmTk extends SimbaModel
{
    protected function cdate(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => $value ? Carbon::parse($value)->format('d/m/y') : null
        );
    }

    protected function ldate(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => $value ? Carbon::parse($value)->format('d/m/y') : null
        );
    }
}
