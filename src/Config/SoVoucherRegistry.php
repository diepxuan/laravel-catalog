<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-16 00:27:51
 */

namespace Diepxuan\Catalog\Config;

final class SoVoucherRegistry
{
    /**
     * SO voucher metadata verified from simba-docs/data/SiDmCt.md and sysVoucherInfo.md.
     *
     * @return array<string, array<string, string>>
     */
    public static function vouchers(): array
    {
        return [
            'SO1' => [
                'title'      => 'Đơn đặt hàng',
                'route'      => 'banhang.so1',
                'menuid'     => '06.10.01',
                'sys_menuid' => '06.10.02',
                'ph'         => 'soPh1',
                'ct'         => 'soCt1',
                'table_ph'   => 'SOPH1',
                'table_ct'   => 'SOCT1',
            ],
            'SO4' => [
                'title'    => 'Phiếu nhập hàng bán bị trả lại',
                'route'    => 'banhang.so4',
                'menuid'   => '06.10.11',
                'ph'       => 'soPh4',
                'ct'       => 'soCt4',
                'table_ph' => 'SOPH4',
                'table_ct' => 'SOCT4',
            ],
            'SO5' => [
                'title'    => 'Hoá đơn dịch vụ',
                'route'    => 'banhang.so5',
                'menuid'   => '06.10.14',
                'ph'       => 'soPh5',
                'ct'       => 'soCt5',
                'table_ph' => 'SOPH5',
                'table_ct' => 'SOCT5',
            ],
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
