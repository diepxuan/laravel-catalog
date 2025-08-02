<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-08-02 23:49:27
 */

namespace Diepxuan\Catalog\Models;

use Diepxuan\Simba\Models\InDmKho as Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class InDmKho extends Model
{
    /**
     * Gọi stored procedure asINGetDMKHO để lấy dữ liệu Danh sách kho.
     *
     * @return array
     */
    public static function getAsINGetDMKHO(array $params = []): Collection
    {
        return self::hydrate(parent::getAsINGetDMKHO([
            'pMa_Cty' => $params['pMa_Cty'] ??  \CatalogService::company()->id,
            'pMa_kho' => $params['pMa_kho'] ?? null,
            'pStruct' => $params['pStruct'] ?? null,
        ])->toArray());
    }

    protected function cdate(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => optional(Carbon::parse($value ?? $attributes['cdate'] ?? $attributes['cDate'] ?? null))->format('d/m/y')
        );
    }

    protected function ldate(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => optional(Carbon::parse($value ?? $attributes['ldate'] ?? $attributes['lDate'] ?? null))->format('d/m/y')
        );
    }

    protected function cuser(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => $value ?? $attributes['cuser'] ?? $attributes['cUser']
        );
    }

    protected function luser(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => $value ?? $attributes['luser'] ?? $attributes['lUser']
        );
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
