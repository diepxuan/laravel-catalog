<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-06-05 14:47:21
 */

namespace Diepxuan\Catalog\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Params extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected function pMaCty(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => $value ?? \CatalogService::company()->ma_cty
        );
    }

    protected function pMaNt(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => $value ?? \CatalogService::ma_Nt()
        );
    }

    protected function pNgay(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => Carbon::parse($value ?? '2020-01-01')
        );
    }
}
