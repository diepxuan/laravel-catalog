<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-06-17 07:43:17
 */

namespace Diepxuan\Catalog\Models\Simba;

use Diepxuan\Catalog\Models\Product as DProduct;
use Diepxuan\Simba\Models\Product;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SProduct extends Product
{
    public function product(): BelongsTo
    {
        return $this->belongsTo(DProduct::class, 'ma_vt', 'sku');
    }
}
