<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-06-19 00:11:09
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
     * Gọi function afDuDauTk để lấy dữ liệu đầu kỳ.
     *
     * @return array
     */
    public function scopeAfDuDauTk(Builder $query, array $params): Builder
    {
        $params = [
            'pMaCty' => $params['pMaCty'] ?? '111',
            'pTk'    => $params['pTk'] ?? '111',
            'pNgay'  => $params['pNgay'] ?? '2020-01-01',
            'pMaNt'  => $params['pMaNt'] ?? 'VND',
            'pNam'   => $params['pNam'] ?? \CatalogFunctions::afNamTC([
                'pMaCty' => $params['pMaCty'] ?? '111',
                'pNgay'  => $params['pNgay'] ?? '2020-01-01',
            ]),
            'pNgayDnTc' => $params['pNgayDnTc'] ?? \CatalogFunctions::afNgay_DNTC([
                'pMaCty' => $params['pMaCty'] ?? '111',
                'pNam'   => $params['pNam'] ?? '2020',
            ]),
        ];

        return $query
            ->select('ma_cty', 'tk', 'ma_nt')
            ->selectRaw('SUM(ps_no - ps_co) as du_no')
            ->selectRaw('0 as du_co')
            ->selectRaw('SUM(ps_no_nt - ps_co_nt) as du_no_nt')
            ->selectRaw('0 as du_co_nt')
            ->filterByMaCty($params['pMaCty'])
            ->filterByTkList($params['pTk'] ?? '')
            ->filterByMaNt($params['pMaNt'] ?? '')
            ->whereBetween('Ngay_ct', [$params['pNgayDnTc'], $params['pNgay']])
            ->where('Ngay_ct', '<', $params['pNgay'])
            ->groupBy('ma_cty', 'tk', 'ma_nt')
        ;
    }

    /**
     * Gọi stored procedure asCARptTMNH01 để lấy dữ liệu báo cáo tiền mặt ngân hàng.
     *
     * @return array
     */
    public function scopeGetCARptTMNH(Builder $query, array $params): Builder
    {
        $params = [
            'pMa_Cty'   => $params['ma_Cty'] ?? '111',
            'pTk'       => $params['tk'] ?? '111',
            'pNgay_Ct1' => $params['ngay_Ct1'],
            'pNgay_Ct2' => $params['ngay_Ct2'],
            'pMa_Nt'    => $params['ma_Nt'] ?? 'VND',
        ];

        return $query
            ->filterByMaCty($params['pMa_Cty'])
            ->filterByNgayCt($params['pNgay_Ct1'], $params['pNgay_Ct2'])
            ->filterByTkList($params['pTk'] ?? '')
            ->filterByMaNt($params['pMa_Nt'] ?? '')
            ->with('arDmKh')
            ->orderBy('ngay_ct')
            ->orderBy('so_ct')
            ->orderBy('stt_rec')
        ;
    }

    /**
     * Gọi stored procedure asGLRptNKC03 để lấy dữ liệu sổ nhật ký thu/chi.
     *
     * @return Collection
     */
    public function scopeGetNKThuChi(Builder $query, array $params): Builder
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
            ->with('arDmKh')
            // ->where('ps_no', '<>', 0)
            ->orderBy('ngay_ct')->orderBy('ma_ct')->orderBy('so_ct')
        ;
    }

    public function arDmKh()
    {
        return $this->belongsTo(ArDmKh::class, 'ma_kh', 'ma_kh');
    }

    /**
     * Gọi stored procedure asCARptTMNH01 để lấy dữ liệu báo cáo tiền mặt ngân hàng.
     *
     * @return array
     */
    public static function getCARptTMNH01(array $params): Collection
    {
        return self::hydrate(parent::getCARptTMNH01($params)->toArray());
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

    protected function soCt(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => $value ?? $attributes['so_ct'] ?? $attributes['So_ct']
        );
    }

    protected function dienGiai(): Attribute
    {
        return Attribute::get(
            static function ($value, array $attributes) {
                $value ??= $attributes['dien_giai'] ?? $attributes['Dien_giai'];

                switch ($value) {
                    case '#CARptTMNH_DDK':
                        return 'Dư đầu kỳ';

                    case '#CARptTMNH_DCK':
                        return 'Dư cuối kỳ';

                    case '#CARptTMNH_TPS':
                        return 'Tổng phát sinh trong kỳ';

                    default:
                        return $value;
                }
            }
        );
    }

    protected function psNo(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => $value ?? $attributes['ps_no'] ?? $attributes['Ps_no']
        );
    }

    protected function psCo(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => $value ?? $attributes['ps_co'] ?? $attributes['Ps_co']
        );
    }

    protected function ngayLct(): Attribute
    {
        return Attribute::get(
            static fn ($value, array $attributes) => $value ? Carbon::parse($value)->format('d/m/Y') : null
        );
    }
}
