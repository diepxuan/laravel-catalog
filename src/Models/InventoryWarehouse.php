<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-05-30 07:48:22
 */

namespace Diepxuan\Catalog\Models;

use Diepxuan\Simba\Models\KhoHang;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryWarehouse extends KhoHang
{
    // /**
    //  * Get the Product that owns the comment.
    //  */
    // public function product(): BelongsTo
    // {
    //     return $this->belongsTo(Product::class);
    // }
}
