<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-06-19 10:41:01
 */

namespace Diepxuan\Catalog\Models;

use Diepxuan\Catalog\Models\Simba\SProduct;
use Diepxuan\Catalog\Observers\ProductObserver;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

#[ObservedBy([ProductObserver::class])]
class Product extends AbstractModel
{
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'quantity' => 'float',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return $this->casts;
    }

    /**
     * Get the Simba Product Id.
     */
    protected function simbaId(): Attribute
    {
        return Attribute::make(
            get: static fn (mixed $value, array $attributes) => "001_{$attributes['sku']}",
        );
    }

    protected function urlKey(): Attribute
    {
        return Attribute::make(
            get: static fn (mixed $value, array $attributes) => Str::of(vn_convert_encoding($attributes['name']))->slug('-'),
        );
    }

    protected function catIds(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $this->cat ? $this->cat->ids : [],
        );
    }

    /**
     * Get the Category.
     */
    protected function cat(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category', 'sku');
    }

    protected function sProduct(): HasOne
    {
        return $this->hasOne(SProduct::class, 'ma_vt', 'sku');
    }
}
