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

final class SoVoucherRegistry
{
    /**
     * @return array<string, array<string, string>>
     */
    public static function vouchers(): array
    {
        return SimbaVoucherMetadata::forCodes(['SO1', 'SO4', 'SO5'], [
            'SO1' => ['route' => 'banhang.so1', 'sys_menuid' => '06.10.02'],
            'SO4' => ['route' => 'banhang.so4'],
            'SO5' => ['route' => 'banhang.so5'],
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
