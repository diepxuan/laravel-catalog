<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-07-05 20:25:52
 */

namespace Diepxuan\Catalog\Models;

use Diepxuan\Simba\Models\SoPh3 as Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class SoPh3 extends Model
{
    /**
     * Gọi stored procedure asSoFilt3 để lấy dữ liệu danh sách bán hàng.
     */
    public static function getAsSoFilt3(array $params): array|Collection
    {
        $params = (object) $params;
        $result = parent::getAsSoFilt3([
            'ma_cty'   => $params->pMa_Cty,
            'ma_ct'    => $params->pMa_CT,
            'ngay_ct1' => $params->pNgay_Ct1,
            'ngay_ct2' => $params->pNgay_Ct2,
            'pMa_Kh'   => $params->pMa_Kh,
            'pMa_Nt'   => $params->pMa_Nt,
            'pMa_Bp'   => $params->pMa_Bp,
        ]);
        $result['phs'] = self::hydrate($result['phs']);

        return $result;
    }

    protected function ngayCt(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => ($value = $attributes['ngay_ct'] ?? $attributes['Ngay_ct'] ?? null)
                ? (
                    ($date = Carbon::parse($value))->isSameDay(Carbon::create(1_900, 1, 1))
                    ? ' / / '
                    : $date->format('d/m/Y')
                ) : ' / / '
        );
    }
}
