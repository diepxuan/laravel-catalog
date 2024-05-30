<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-05-30 08:59:11
 */

namespace Diepxuan\Catalog\Models;

use Diepxuan\Simba\Models\PhieuChuyenKhoCT;
use Illuminate\Database\Eloquent\Relations\HasOne;

class InventoryTicketItem extends PhieuChuyenKhoCT
{
    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'sku', 'ma_vt');
    }
}
