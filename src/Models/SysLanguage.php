<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-07-12 15:33:30
 */

namespace Diepxuan\Catalog\Models;

use Diepxuan\Simba\Models\SysLanguage as Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class SysLanguage extends Model
{
    protected function name(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => $value ?? $attributes['name'] ?? $attributes['Name'] ?? null
        );
    }
}
