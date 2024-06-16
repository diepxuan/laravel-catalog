<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-06-16 12:47:04
 */

namespace Diepxuan\Catalog\Models\Simba;

use Diepxuan\Catalog\Models\Category as DCategory;
use Diepxuan\Simba\Models\Category;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class SCategory extends Category
{
    public function category(): BelongsTo
    {
        return $this->belongsTo(DCategory::class, 'ma_nhvt', 'sku');
    }

    protected function urlKey(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => Str::of(vn_convert_encoding($this->name))->slug('-'),
        );
    }
}
