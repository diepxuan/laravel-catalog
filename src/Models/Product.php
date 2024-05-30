<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-05-30 08:56:24
 */

namespace Diepxuan\Catalog\Models;

use Diepxuan\Catalog\Observers\ProductObserver;
use Diepxuan\Simba\Models\Product as SProduct;
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'float',
        ];
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

    /**
     * Get the Category urlKey.
     */
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

    protected function simba(): HasOne
    {
        return $this->hasOne(SProduct::class, 'ma_vt', 'sku');
    }
}
