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

final class InVoucherRegistry
{
    /**
     * IN voucher metadata verified from simba-docs/data/SiDmCt.md and sysVoucherInfo.md.
     *
     * @return array<string, array<string, string>>
     */
    public static function vouchers(): array
    {
        return [
            'IN1' => ['title' => 'Phiếu nhập kho', 'route' => 'in.vch.in1', 'menuid' => '14.10.02', 'ph' => 'inPh1', 'ct' => 'inCt1', 'table_ph' => 'INPH1', 'table_ct' => 'INCT1'],
            'IN2' => ['title' => 'Phiếu xuất kho', 'route' => 'in.vch.in2', 'menuid' => '14.10.05', 'ph' => 'inPh2', 'ct' => 'inCt2', 'table_ph' => 'INPH2', 'table_ct' => 'INCT2'],
            'IN3' => ['title' => 'Phiếu xuất chuyển kho', 'route' => 'in.vch.in3', 'menuid' => '14.10.08', 'ph' => 'inPh3', 'ct' => 'inCt3', 'table_ph' => 'INPH3', 'table_ct' => 'INCT3'],
            'IN4' => ['title' => 'Phiếu nhập điều chuyển', 'route' => 'in.vch.in4', 'menuid' => '14.10.08', 'ph' => 'inPh3', 'ct' => 'inCt3', 'table_ph' => 'INPH3', 'table_ct' => 'INCT3'],
            'IN5' => ['title' => 'Phiếu xuất công cụ dụng cụ', 'route' => 'in.vch.in5', 'menuid' => '14.10.11', 'ph' => 'inPh5', 'ct' => 'inCt5', 'table_ph' => 'INPH5', 'table_ct' => 'INCT5'],
            'IN6' => ['title' => 'Phiếu tháo dỡ lắp ráp', 'route' => 'in.vch.in6', 'menuid' => '', 'sys_menuid' => '14.10.14', 'ph' => 'inPh6', 'ct' => 'inCt6', 'table_ph' => 'INPH6', 'table_ct' => 'INCT6M'],
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
