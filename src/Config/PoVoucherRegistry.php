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
use Diepxuan\Simba\Models\PoPh0;
use Diepxuan\Simba\Models\PoPh1;
use Diepxuan\Simba\Models\PoPh2;
use Diepxuan\Simba\Models\PoPh3;
use Diepxuan\Simba\Models\PoPh4;
use Diepxuan\Simba\Models\PoPh5;
use Diepxuan\Simba\Models\PoPh6;
use Diepxuan\Simba\Models\PoPh8;

final class PoVoucherRegistry
{
    /**
     * @return array<string, array<string, string>>
     */
    public static function vouchers(): array
    {
        return SimbaVoucherMetadata::forCodes(['PO0', 'PO1', 'PO2', 'PO3', 'PO4', 'PO5', 'PO6', 'PO7', 'PO8'], [
            'PO0' => ['route' => 'muahang.po0', 'model' => PoPh0::class],
            'PO1' => ['route' => 'muahang.po1', 'sys_menuid' => '10.10.05', 'model' => PoPh1::class],
            'PO2' => ['route' => 'muahang.po2', 'model' => PoPh2::class],
            'PO3' => ['route' => 'muahang.hoadonmua', 'cp' => 'poCp3', 'model' => PoPh3::class],
            'PO4' => ['route' => 'muahang.po4', 'cp' => 'poCp4', 'model' => PoPh4::class],
            'PO5' => ['route' => 'muahang.po5', 'model' => PoPh5::class],
            'PO6' => ['route' => 'muahang.po6', 'model' => PoPh6::class],
            'PO7' => ['route' => 'muahang.po7', 'cp' => 'poCp3', 'model' => PoPh3::class],
            'PO8' => ['route' => 'muahang.po8', 'cp' => 'poCp8', 'model' => PoPh8::class],
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
