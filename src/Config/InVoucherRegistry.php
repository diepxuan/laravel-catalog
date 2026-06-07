<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-06-06 23:34:00
 */

namespace Diepxuan\Catalog\Config;

use Diepxuan\Catalog\Services\SimbaVoucherMetadata;

final class InVoucherRegistry
{
    /**
     * @return array<string, array<string, string>>
     */
    public static function vouchers(): array
    {
        return SimbaVoucherMetadata::forCodes(['IN1', 'IN2', 'IN3', 'IN4', 'IN5', 'IN6'], [
            'IN1' => ['route' => 'in.vch.in1'],
            'IN2' => ['route' => 'in.vch.in2'],
            'IN3' => ['route' => 'in.vch.in3'],
            'IN4' => ['route' => 'in.vch.in4'],
            'IN5' => ['route' => 'in.vch.in5'],
            'IN6' => ['route' => 'in.vch.in6', 'sys_menuid' => '14.10.14', 'table_ct' => 'INCT6M'],
        ]);
    }

    /**
     * @return null|array<string, string>
     */
    public static function get(string $voucherCode): ?array
    {
        return self::vouchers()[strtoupper($voucherCode)] ?? null;
    }
}
