<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-16 00:28:17
 */

namespace Diepxuan\Catalog\Tests\Unit\Services;

use Diepxuan\Catalog\Services\SimbaDocsMenuRepository;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class SimbaDocsMenuRepositoryTest extends TestCase
{
    public function testActiveMenusAreLoadedFromSimbaDocsData(): void
    {
        $repository = new SimbaDocsMenuRepository(\dirname(__DIR__, 5));
        $menus      = $repository->activeMenus();

        self::assertGreaterThan(300, $menus->count());
        self::assertSame('02.00.00', $menus->first()->menuid);
        self::assertSame('Tổng hợp', $menus->first()->getDisplayName());
    }

    public function testVoucherMenuMetadataKeepsSimbaFields(): void
    {
        $repository = new SimbaDocsMenuRepository(\dirname(__DIR__, 5));
        $po3        = $repository->activeMenus()->firstWhere('menuid', '10.10.14');

        self::assertNotNull($po3);
        self::assertTrue($po3->isVoucher());
        self::assertSame('PO', $po3->moduleid);
        self::assertSame('PO3', $po3->code_name);
    }
}
