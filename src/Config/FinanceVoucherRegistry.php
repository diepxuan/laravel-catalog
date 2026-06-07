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

final class FinanceVoucherRegistry
{
    /**
     * @return array<string, array<string, string>>
     */
    public static function vouchers(): array
    {
        return SimbaVoucherMetadata::forCodes(['GL1', 'NB1', 'AR4', 'AP4'], [
            'GL1' => ['route' => 'gl.vch.gl1'],
            'NB1' => ['route' => 'gl.vch.nb1', 'table_ph' => 'GLNB', 'table_ct' => 'GLNB'],
            'AR4' => ['route' => 'ar.vch.ar4', 'menuid' => '06.10.29', 'sys_menuid' => '08.10.11'],
            'AP4' => ['route' => 'ap.vch.ap4', 'sys_menuid' => '12.10.11'],
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
