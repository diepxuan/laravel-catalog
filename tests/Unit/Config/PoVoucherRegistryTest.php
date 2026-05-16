<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-16 00:28:13
 */

namespace Diepxuan\Catalog\Tests\Unit\Config;

use Diepxuan\Catalog\Config\PoVoucherRegistry;
use Diepxuan\Catalog\Services\SimbaDocsDataRepository;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class PoVoucherRegistryTest extends TestCase
{
    public function testPoVoucherRegistryMatchesSiDmCt(): void
    {
        $data     = new SimbaDocsDataRepository(\dirname(__DIR__, 5));
        $vouchers = $data->table('SiDmCt.md')->keyBy('ma_ct');

        foreach (PoVoucherRegistry::vouchers() as $voucherCode => $metadata) {
            self::assertTrue($vouchers->has($voucherCode), "Missing {$voucherCode} in SiDmCt.md");

            $source = $vouchers->get($voucherCode);
            self::assertSame($source['menuid'], $metadata['menuid']);
            self::assertSame($source['ph'], $metadata['ph']);
            self::assertSame($source['ct'], $metadata['ct']);
        }
    }

    public function testPoVoucherRegistryModelsExist(): void
    {
        foreach (PoVoucherRegistry::vouchers() as $voucherCode => $metadata) {
            self::assertTrue(class_exists($metadata['model']), "Missing model for {$voucherCode}");
        }
    }
}
