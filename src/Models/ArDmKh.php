<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-01-11 23:03:33
 */

namespace Diepxuan\Catalog\Models;

use Diepxuan\Simba\Models\ArDmKh as Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class ArDmKh extends Model
{
    public function glCts(): HasMany
    {
        return $this->hasMany(GlCt::class, 'ma_kh', 'ma_kh');
    }

    public function getSoduKh($params): Collection
    {
        $params = [
            'pMa_Cty' => $params['pMa_Cty'] ?? static::CTY,
            'pMa_Kh'  => $params['pMa_Kh'] ?? $this->ma_kh,
            'pTk'     => $params['pTk'],
            'pNgay'   => $params['pNgay'] ?? now()->toDateString(),
        ];

        return static::getGetSoDuKh($params);
    }

    /**
     * Gọi stored procedure asGetSoDuKh để lấy số dư khách hàng.
     *
     * @return array
     */
    public static function getGetSoDuKh(array $params): Collection
    {
        return self::hydrate(parent::getGetSoDuKh($params)->toArray());
    }

    protected static function booted(): void
    {
        static::addGlobalScope('orderByMaKh', static function ($query): void {
            $query->orderBy('ma_kh');
        });
    }
}
