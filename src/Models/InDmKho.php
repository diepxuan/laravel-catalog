<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-08-02 21:45:17
 */

namespace Diepxuan\Catalog\Models;

use Diepxuan\Simba\Models\InDmKho as Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Collection;

class InDmKho extends Model
{
    /**
     * Gọi stored procedure asINGetDMKHO để lấy dữ liệu Danh sách kho.
     *
     * @return array
     */
    public static function getAsINGetDMKHO(array $params): Collection
    {
        return self::hydrate(parent::getAsINGetDMKHO($params)->toArray());
    }

    protected function khoDl(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => ($value ?? $attributes['kho_dl'] ?? false) ? '✔' : ''
        );
    }

    protected function ksd(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => ($value ?? $attributes['ksd'] ?? $attributes['KSD'] ?? false) ? '✔' : ''
        );
    }
}
