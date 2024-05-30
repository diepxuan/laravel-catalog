<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-05-30 09:34:20
 */

namespace Diepxuan\Catalog\Models;

use Diepxuan\Simba\Models\PhieuChuyenKho;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryTicket extends PhieuChuyenKho
{
    public function scopeWhereNgayCt($query, $fromDate, $toDate)
    {
        return $this->scopeWhereDateBetween($query, 'ngay_ct', $fromDate, $toDate);
    }

    public function scopeWhereKhoXuat($query, $maKhoXuat)
    {
        return $query->whereHas('items', static function (Builder $query) use ($maKhoXuat): void {
            $query->where('Ma_KhoX', 'like', "{$maKhoXuat}%");
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(InventoryTicketItem::class, 'stt_rec', 'stt_rec');
    }
}
