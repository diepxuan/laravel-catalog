<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-12-24 21:44:29
 */

namespace Diepxuan\Catalog\Models;

use Diepxuan\Catalog\Observers\ProductObserver;
use Diepxuan\Simba\Models\Product as SProduct;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[ObservedBy([ProductObserver::class])]
class Product extends SProduct
{
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'quantity' => 'decimal:1',
        'gia_nt2'  => 'float',
        'price'    => 'float',
    ];

    protected function urlKey(): Attribute
    {
        return Attribute::make(
            get: static fn (mixed $value, array $attributes) => Str::of(vn_convert_encoding($this->name))->slug('-'),
        );
    }

    /**
     * Get the Category.
     */
    protected function cat(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'ma_nhvt', 'ma_nhvt');
    }
}
