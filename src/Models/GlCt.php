<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-05-31 12:35:23
 */

namespace Diepxuan\Catalog\Models;

use Diepxuan\Simba\Models\GlCt as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class GlCt extends Model
{
    /**
     * Gọi stored procedure asGLRptNKC03 để lấy dữ liệu sổ nhật ký thu/chi.
     *
     * @return Collection
     */
    public function scopeGetNKThu(Builder $query, array $params): Builder
    {
        $params = [
            'pMa_Cty'    => $params['ma_cty'] ?? '',
            'pNgay_Ct1'  => $params['ngay_ct1'] ?? '2000-01-01',
            'pNgay_Ct2'  => $params['ngay_ct2'] ?? now(),
            'pTk_List'   => $params['tk_list'] ?? '111,112',
            'pTkdu_List' => $params['tkdu_list'] ?? '',
            'pMa_Bp'     => $params['ma_bp'] ?? '',
            'pMa_Nt'     => $params['ma_nt'] ?? 'VND',
        ];

        return $query
            ->filterByMaCty($params['pMa_Cty'])
            ->filterByNgayCt($params['pNgay_Ct1'], $params['pNgay_Ct2'])
            ->filterByTkList($params['pTk_List'] ?? '')
            ->filterByTkduList($params['pTkdu_List'] ?? '')
            ->filterByMaBp($params['pMa_Bp'] ?? '')
            ->filterByMaNt($params['pMa_Nt'] ?? '')
            // ->where('ps_no', '<>', 0)
            ->orderBy('ngay_ct')->orderBy('ma_ct')->orderBy('so_ct')
        ;
    }

    protected function ngayCt(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => $value ? Carbon::parse($value)->format('d/m/Y') : null
        );
    }

    protected function ngayLct(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => $value ? Carbon::parse($value)->format('d/m/Y') : null
        );
    }
}
