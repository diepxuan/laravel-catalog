<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-06-08 20:32:32
 */

namespace Diepxuan\Catalog\Services;

use Diepxuan\Catalog\Models\GlCdTk;
use Diepxuan\Catalog\Models\GlCt;
use Diepxuan\Catalog\Models\Params;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CatalogFunctions
{
    public function afDuDauTk(array|Params $params)
    {
        $params = \is_array($params) ? new Params($params) : $params;
        $params->pMaCty ??= \CatalogService::company()->ma_cty;
        $params->pTk ??= '111';
        $params->pNgay ??= '2020-01-01';
        $params->pMaNt ??= \CatalogService::ma_Nt();
        $params->pNamTc ??= static::afNamTC($params);
        $params->pNgayDnTc ??= static::afNgay_DNTC($params);
        // \Debugbar::info($params);

        // Theo đồng tiền hạch toán
        $du_dau_nam = GlCdTk::afDuDauTk([
            'pMa_Cty' => $params->pMaCty,
            'pTk'     => $params->pTk,
            'pNgay'   => $params->pNgay,
            'pMa_Nt'  => $params->pMaNt,
            'pNam'    => $params->pNamTc,
        ]);

        $ps_dau_ky = GlCt::afDuDauTk([
            'pMaCty'    => $params->pMaCty,
            'pTk'       => $params->pTk,
            'pNgay'     => $params->pNgay,
            'pMaNt'     => $params->pMaNt,
            'pNam'      => $params->pNamTc,
            'pNgayDnTc' => $params->pNgayDnTc,
        ]);

        $glCtDk = $ps_dau_ky->toBase()->unionAll($du_dau_nam->toBase())
        // ->select('ma_cty', 'tk', 'ma_nt', DB::raw('SUM(du_no) as du_no'), DB::raw('SUM(du_co) as du_co'), DB::raw('SUM(du_no_nt) as du_no_nt'), DB::raw('SUM(du_co_nt) as du_co_nt'))
        // ->where(static function ($q): void {
        //     $q->where('du_no', '<>', 0)->orWhere('du_no_nt', '<>', 0);
        // })
        // ->groupBy('ma_cty', 'tk', 'ma_nt')
            ->get()
            ->map(static function ($glCt) use ($params) {
                $glCt->tk       = $params->pTk;
                $glCt->du_no    = (float) $glCt->du_no;
                $glCt->du_co    = (float) $glCt->du_co;
                $glCt->du_no_nt = (float) $glCt->du_no_nt;
                $glCt->du_co_nt = (float) $glCt->du_co_nt;

                return $glCt;
            })
        ;

        return (object) [
            'du_no'    => $glCtDk->sum(static fn ($i) => (float) $i->du_no - (float) $i->du_co),
            'du_co'    => $glCtDk->sum(static fn ($i) => 0),
            'du_no_nt' => $glCtDk->sum(static fn ($i) => (float) $i->du_no_nt - (float) $i->du_co_nt),
            'du_co_nt' => $glCtDk->sum(static fn ($i) => 0),
        ];
    }

    public function afNamTC(array|Params $params)
    {
        $params = \is_array($params) ? new Params($params) : $params;
        $params->pNam ??= $params->pNgay->year;
        $params->pNgayDnTc ??= $this->afNgay_DNTC($params);
        // \Debugbar::info($params, $pNam);

        return $params->pNgay->lt($params->pNgayDnTc) ? $params->pNgay->year - 1 : $params->pNgay->year;
    }

    public function afNgay_DNTC(array|Params $params): Carbon
    {
        $params = \is_array($params) ? new Params($params) : $params;

        return \CatalogService::company()->siSetup->ngay_dntc->setYear($params->pNam);
    }
}
