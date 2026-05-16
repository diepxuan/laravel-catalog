<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-16 00:27:45
 */

namespace Diepxuan\Catalog\Config;

final class FinanceVoucherRegistry
{
    /**
     * Voucher metadata verified from simba-docs/data/SiDmCt.md and sysVoucherInfo.md.
     *
     * @return array<string, array<string, string>>
     */
    public static function vouchers(): array
    {
        return [
            'GL1' => ['title' => 'Phiếu kế toán', 'route' => 'gl.vch.gl1', 'menuid' => '02.10.02', 'ph' => 'glPh1', 'ct' => 'glCt1', 'table_ph' => 'GLPH1', 'table_ct' => 'GLCT1'],
            'NB1' => ['title' => 'Chứng từ ngoại bảng', 'route' => 'gl.vch.nb1', 'menuid' => '02.10.17', 'ph' => '', 'ct' => '', 'table_ph' => 'GLNB', 'table_ct' => 'GLNB'],
            'AR4' => ['title' => 'Phiếu bù trừ công nợ phải thu', 'route' => 'ar.vch.ar4', 'menuid' => '06.10.29', 'sidmct_menuid' => '08.10.11', 'ph' => 'arPh4', 'ct' => 'arCt4', 'table_ph' => 'ARPH4', 'table_ct' => 'ARCT4'],
            'AP4' => ['title' => 'Phiếu bù trừ công nợ phải trả', 'route' => 'ap.vch.ap4', 'menuid' => '10.10.38', 'sidmct_menuid' => '10.10.38', 'sys_menuid' => '12.10.11', 'ph' => 'apPh4', 'ct' => 'apCt4', 'table_ph' => 'APPH4', 'table_ct' => 'APCT4'],
        ];
    }

    /**
     * @return null|array<string, string>
     */
    public static function get(string $voucherCode): ?array
    {
        return self::vouchers()[strtoupper($voucherCode)] ?? null;
    }
}
