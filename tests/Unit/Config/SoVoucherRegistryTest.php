<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-16 00:28:16
 */

namespace Diepxuan\Catalog\Tests\Unit\Config;

use Diepxuan\Catalog\Config\SoVoucherRegistry;
use Diepxuan\Catalog\Services\SimbaDocsDataRepository;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class SoVoucherRegistryTest extends TestCase
{
    public function testSoVoucherRegistryMatchesSiDmCt(): void
    {
        $data     = new SimbaDocsDataRepository(\dirname(__DIR__, 5));
        $vouchers = $data->table('SiDmCt.md')->keyBy('ma_ct');

        foreach (SoVoucherRegistry::vouchers() as $voucherCode => $metadata) {
            self::assertTrue($vouchers->has($voucherCode), "Missing {$voucherCode} in SiDmCt.md");

            $source = $vouchers->get($voucherCode);
            self::assertSame($source['menuid'], $metadata['menuid']);
            self::assertSame($source['ph'], $metadata['ph']);
            self::assertSame($source['ct'], $metadata['ct']);
        }
    }

    public function testSoVoucherRegistryMatchesSysVoucherInfo(): void
    {
        $data     = new SimbaDocsDataRepository(\dirname(__DIR__, 5));
        $vouchers = $data->table('sysVoucherInfo.md')->keyBy('voucher_code');

        foreach (SoVoucherRegistry::vouchers() as $voucherCode => $metadata) {
            self::assertTrue($vouchers->has($voucherCode), "Missing {$voucherCode} in sysVoucherInfo.md");

            $source = $vouchers->get($voucherCode);
            self::assertSame($metadata['table_ph'], $source['ph_table_name']);
            self::assertSame($metadata['table_ct'], $source['ct_table_name']);
        }
    }
}
