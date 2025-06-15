<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-06-07 13:26:48
 */

namespace Diepxuan\Catalog\Models;

use Diepxuan\Simba\Models\GlCdTk as Model;
use Illuminate\Database\Eloquent\Builder;

class GlCdTk extends Model
{
    /**
     * Gọi function afDuDauTk để lấy dữ liệu đầu kỳ.
     *
     * @return array
     */
    public function scopeAfDuDauTk(Builder $query, array $params): Builder
    {
        $params = [
            'pMa_Cty' => $params['pMa_Cty'] ?? '111',
            'pTk'     => $params['pTk'] ?? '111',
            'pNgay'   => $params['pNgay'] ?? '2020-01-01',
            'pMa_Nt'  => $params['pMa_Nt'] ?? 'VND',
            'pNam'    => $params['pNam'] ?? \CatalogFunctions::afNamTC([
                'pMa_Cty' => $params['pMa_Cty'] ?? '111',
                'pNgay'   => $params['pNgay'] ?? '2020-01-01',
            ]),
        ];

        return $query
            ->select('ma_cty', 'tk', 'ma_nt')
            ->when(
                $params['pMa_Nt'] === \CatalogService::ma_Nt(),
                static fn ($query) => $query
                    // ->selectRaw('SUM(du_no - du_co) as du_no')
                    // ->selectRaw('0 as du_co')
                    // ->selectRaw('SUM(du_no_nt - du_co_nt) as du_no_nt')
                    // ->selectRaw('0 as du_co_nt'),
                    ->selectRaw('SUM(du_no) as du_no')
                    ->selectRaw('SUM(du_co) as du_co')
                    ->selectRaw('SUM(du_no_nt) as du_no_nt')
                    ->selectRaw('SUM(du_co_nt) as du_co_nt'),
                static fn ($query) => $query
                    // ->selectRaw('SUM(du_no00 - du_co00) as du_no')
                    // ->selectRaw('0 as du_co')
                    // ->selectRaw('SUM(du_no_nt00 - du_co_nt00) as du_no_nt')
                    // ->selectRaw('0 as du_co_nt')
                    ->selectRaw('SUM(du_no00) as du_no')
                    ->selectRaw('SUM(du_co00) as du_co')
                    ->selectRaw('SUM(du_no_nt00) as du_no_nt')
                    ->selectRaw('SUM(du_co_nt00) as du_co_nt')
            )
            ->filterByMaCty($params['pMa_Cty'])
            ->filterByTkList($params['pTk'] ?? '')
            ->filterByMaNt($params['pMa_Nt'] ?? '')
            ->where('nam', $params['pNam'])
            ->groupBy('ma_cty', 'tk', 'ma_nt')
        ;
    }
}
