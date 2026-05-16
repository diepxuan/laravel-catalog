<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-16 00:28:14
 */

namespace Diepxuan\Catalog\Tests\Unit\Config;

use Diepxuan\Catalog\Config\SimbaProcessRegistry;
use Diepxuan\Catalog\Config\SimbaRouteRegistry;
use Diepxuan\Catalog\Services\SimbaDocsMenuRepository;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class SimbaProcessRegistryTest extends TestCase
{
    public function testProcessRegistryUsesActiveSysMenuRowsWithTechnicalAnchor(): void
    {
        $menus = (new SimbaDocsMenuRepository(\dirname(__DIR__, 5)))->activeMenus()
            ->mapWithKeys(static fn ($menu): array => [$menu->menuid => $menu])
        ;

        foreach (SimbaProcessRegistry::processes() as $route => $metadata) {
            self::assertTrue($menus->has($metadata['menuid']), "Missing active menu for {$route}");

            $hasTechnicalAnchor = '' !== trim($metadata['dll_name'])
                || '' !== trim($metadata['command'])
                || '' !== trim($metadata['code_name']);

            self::assertTrue($hasTechnicalAnchor, "Missing DLL/command/code_name for {$route}");
        }
    }

    public function testProcessRegistryExcludesMenusAlreadyCoveredByNonProcessRoutes(): void
    {
        $coveredMenuIds = collect(SimbaRouteRegistry::routesWithoutProcesses())
            ->pluck('menuid')
            ->flip()
        ;

        foreach (SimbaProcessRegistry::processes() as $route => $metadata) {
            self::assertFalse($coveredMenuIds->has($metadata['menuid']), "Process {$route} duplicates a non-process route");
        }
    }

    public function testRemainingProcessShellCountsByModule(): void
    {
        $expectedCounts = [
            'CA' => 1,
            'CO' => 10,
            'FA' => 12,
            'GL' => 4,
            'IN' => 10,
            'PO' => 3,
            'SI' => 6,
            'SO' => 2,
        ];

        $counts = [];
        foreach (SimbaProcessRegistry::processes() as $metadata) {
            $module          = $metadata['module'];
            $counts[$module] = ($counts[$module] ?? 0) + 1;
        }

        ksort($counts);

        self::assertSame($expectedCounts, $counts);
        self::assertSame(48, array_sum($counts));
    }
}
