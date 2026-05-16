<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2026-05-16 00:28:15
 */

namespace Diepxuan\Catalog\Tests\Unit\Config;

use Diepxuan\Catalog\Config\SimbaRouteRegistry;
use Diepxuan\Catalog\Services\SimbaDocsDataRepository;
use Diepxuan\Catalog\Services\SimbaDocsMenuRepository;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class SimbaRouteRegistryTest extends TestCase
{
    private SimbaDocsDataRepository $data;
    private SimbaDocsMenuRepository $menus;

    protected function setUp(): void
    {
        parent::setUp();

        $root        = \dirname(__DIR__, 5);
        $this->data  = new SimbaDocsDataRepository($root);
        $this->menus = new SimbaDocsMenuRepository($root, $this->data);
    }

    public function testEveryRegisteredMenuIdExistsInSimbaDocsMenu(): void
    {
        $menuIds = $this->menus->activeMenus()->pluck('menuid')->all();

        foreach (SimbaRouteRegistry::routes() as $route => $metadata) {
            self::assertContains($metadata['menuid'], $menuIds, "Missing menuid for route {$route}");
        }
    }

    public function testVoucherRoutesUseVoucherCodesFromSiDmCt(): void
    {
        $voucherCodes = $this->data->table('SiDmCt.md')->pluck('ma_ct')->unique()->all();

        foreach (SimbaRouteRegistry::routes() as $route => $metadata) {
            if (SimbaRouteRegistry::TYPE_VOUCHER !== $metadata['source_type']) {
                continue;
            }

            self::assertContains($metadata['ma_ct'], $voucherCodes, "Missing voucher code for route {$route}");
        }
    }

    public function testDictionaryRoutesUseDictionaryKeysFromSysDictionaryInfo(): void
    {
        $dictionaryRows = $this->data->table('sysDictionaryInfo.md');
        $dictionaryKeys = $dictionaryRows->pluck('code_name')->unique()->all();

        foreach (SimbaRouteRegistry::routes() as $route => $metadata) {
            if (SimbaRouteRegistry::TYPE_DICTIONARY !== $metadata['source_type']) {
                continue;
            }

            self::assertContains($metadata['dictionary_key'], $dictionaryKeys, "Missing dictionary key for route {$route}");

            $row = $dictionaryRows->firstWhere('code_name', $metadata['dictionary_key']);
            self::assertSame($metadata['source_menuid'] ?? $metadata['menuid'], $row['menuid'], "Wrong dictionary source menuid for route {$route}");
        }
    }

    public function testEveryActionableActiveMenuHasRouteAnchor(): void
    {
        $routeMenuIds = collect(SimbaRouteRegistry::routes())
            ->pluck('menuid')
            ->flip()
        ;

        $reportMenuIds = $this->data->table('sysReportInfo.md')
            ->merge($this->data->table('zSysReportInfo.md'))
            ->pluck('menuid')
            ->filter()
            ->flip()
        ;
        $dictionaryMenuIds = $this->data->table('sysDictionaryInfo.md')
            ->pluck('menuid')
            ->filter()
            ->flip()
        ;
        $voucherMenuIds = $this->data->table('sysVoucherInfo.md')
            ->pluck('menuid')
            ->filter()
            ->flip()
        ;

        foreach ($this->menus->activeMenus() as $menu) {
            $hasMetadata = $reportMenuIds->has($menu->menuid)
                || $dictionaryMenuIds->has($menu->menuid)
                || $voucherMenuIds->has($menu->menuid);
            $hasTechnicalAnchor = '' !== trim((string) $menu->dllName)
                || '' !== trim((string) $menu->command)
                || '' !== trim((string) $menu->code_name);

            if (!$hasMetadata && !$hasTechnicalAnchor) {
                continue;
            }

            self::assertTrue($routeMenuIds->has($menu->menuid), "Missing route anchor for active menu {$menu->menuid}");
        }
    }

    public function testDuplicateMenuIdsAreExplicitlyAllowed(): void
    {
        $allowedDuplicates = [
            '10.90.22' => ['ar.cungcap', 'po.cungcap'],
            '14.10.08' => ['in.vch.in3', 'in.vch.in4'],
        ];

        $routesByMenu = [];
        foreach (SimbaRouteRegistry::routes() as $route => $metadata) {
            $routesByMenu[$metadata['menuid']][] = $route;
        }

        foreach ($routesByMenu as $menuid => $routes) {
            if (1 === \count($routes)) {
                continue;
            }

            sort($routes);

            self::assertArrayHasKey($menuid, $allowedDuplicates, "Unexpected duplicate menuid {$menuid}");
            self::assertSame($allowedDuplicates[$menuid], $routes, "Wrong duplicate routes for menuid {$menuid}");
        }
    }

    public function testActiveSysVoucherInfoMenusUseVoucherRoutes(): void
    {
        $activeMenuIds = $this->menus->activeMenus()
            ->pluck('menuid')
            ->flip()
        ;

        $routesByMenu = [];
        foreach (SimbaRouteRegistry::routes() as $route => $metadata) {
            $routesByMenu[$metadata['menuid']][] = $metadata + ['route' => $route];
        }

        foreach ($this->data->table('sysVoucherInfo.md') as $voucher) {
            $menuid = $voucher['menuid'];
            if (!$activeMenuIds->has($menuid)) {
                continue;
            }

            self::assertArrayHasKey($menuid, $routesByMenu, "Missing route for active voucher menu {$menuid}");
            foreach ($routesByMenu[$menuid] as $route) {
                self::assertSame(
                    SimbaRouteRegistry::TYPE_VOUCHER,
                    $route['source_type'],
                    "Voucher menu {$menuid} is mapped to non-voucher route {$route['route']}",
                );
            }
        }
    }

    public function testRouteAnchorCountsBySourceType(): void
    {
        $expectedCounts = [
            SimbaRouteRegistry::TYPE_CUSTOM     => 62,
            SimbaRouteRegistry::TYPE_DICTIONARY => 47,
            SimbaRouteRegistry::TYPE_REPORT     => 169,
            SimbaRouteRegistry::TYPE_VOUCHER    => 24,
        ];

        $counts = [];
        foreach (SimbaRouteRegistry::routes() as $metadata) {
            $sourceType          = $metadata['source_type'];
            $counts[$sourceType] = ($counts[$sourceType] ?? 0) + 1;
        }

        ksort($counts);

        self::assertSame($expectedCounts, $counts);
        self::assertSame(302, array_sum($counts));
    }

    public function testRouteAnchorCountsByModuleAndSourceType(): void
    {
        $expectedCounts = [
            'AP' => [
                SimbaRouteRegistry::TYPE_CUSTOM     => 0,
                SimbaRouteRegistry::TYPE_DICTIONARY => 0,
                SimbaRouteRegistry::TYPE_REPORT     => 12,
                SimbaRouteRegistry::TYPE_VOUCHER    => 1,
            ],
            'AR' => [
                SimbaRouteRegistry::TYPE_CUSTOM     => 0,
                SimbaRouteRegistry::TYPE_DICTIONARY => 0,
                SimbaRouteRegistry::TYPE_REPORT     => 12,
                SimbaRouteRegistry::TYPE_VOUCHER    => 1,
            ],
            'CA' => [
                SimbaRouteRegistry::TYPE_CUSTOM     => 1,
                SimbaRouteRegistry::TYPE_DICTIONARY => 2,
                SimbaRouteRegistry::TYPE_REPORT     => 7,
                SimbaRouteRegistry::TYPE_VOUCHER    => 4,
            ],
            'CO' => [
                SimbaRouteRegistry::TYPE_CUSTOM     => 12,
                SimbaRouteRegistry::TYPE_DICTIONARY => 6,
                SimbaRouteRegistry::TYPE_REPORT     => 19,
                SimbaRouteRegistry::TYPE_VOUCHER    => 0,
            ],
            'FA' => [
                SimbaRouteRegistry::TYPE_CUSTOM     => 12,
                SimbaRouteRegistry::TYPE_DICTIONARY => 6,
                SimbaRouteRegistry::TYPE_REPORT     => 13,
                SimbaRouteRegistry::TYPE_VOUCHER    => 0,
            ],
            'GL' => [
                SimbaRouteRegistry::TYPE_CUSTOM     => 6,
                SimbaRouteRegistry::TYPE_DICTIONARY => 6,
                SimbaRouteRegistry::TYPE_REPORT     => 49,
                SimbaRouteRegistry::TYPE_VOUCHER    => 2,
            ],
            'IN' => [
                SimbaRouteRegistry::TYPE_CUSTOM     => 13,
                SimbaRouteRegistry::TYPE_DICTIONARY => 8,
                SimbaRouteRegistry::TYPE_REPORT     => 21,
                SimbaRouteRegistry::TYPE_VOUCHER    => 6,
            ],
            'PO' => [
                SimbaRouteRegistry::TYPE_CUSTOM     => 4,
                SimbaRouteRegistry::TYPE_DICTIONARY => 7,
                SimbaRouteRegistry::TYPE_REPORT     => 12,
                SimbaRouteRegistry::TYPE_VOUCHER    => 6,
            ],
            'SI' => [
                SimbaRouteRegistry::TYPE_CUSTOM     => 11,
                SimbaRouteRegistry::TYPE_DICTIONARY => 5,
                SimbaRouteRegistry::TYPE_REPORT     => 1,
                SimbaRouteRegistry::TYPE_VOUCHER    => 0,
            ],
            'SO' => [
                SimbaRouteRegistry::TYPE_CUSTOM     => 3,
                SimbaRouteRegistry::TYPE_DICTIONARY => 7,
                SimbaRouteRegistry::TYPE_REPORT     => 23,
                SimbaRouteRegistry::TYPE_VOUCHER    => 4,
            ],
        ];

        $counts = [];
        foreach (SimbaRouteRegistry::routes() as $metadata) {
            $module     = $metadata['module'];
            $sourceType = $metadata['source_type'];

            $counts[$module] ??= [
                SimbaRouteRegistry::TYPE_CUSTOM     => 0,
                SimbaRouteRegistry::TYPE_DICTIONARY => 0,
                SimbaRouteRegistry::TYPE_REPORT     => 0,
                SimbaRouteRegistry::TYPE_VOUCHER    => 0,
            ];
            ++$counts[$module][$sourceType];
        }

        ksort($counts);

        self::assertSame($expectedCounts, $counts);
    }

    public function testRemainingActiveMenusWithoutRoutesAreNonActionableContainers(): void
    {
        $routeMenuIds = collect(SimbaRouteRegistry::routes())
            ->pluck('menuid')
            ->flip()
        ;

        $remaining = [];
        foreach ($this->menus->activeMenus() as $menu) {
            if ($routeMenuIds->has($menu->menuid)) {
                continue;
            }

            $hasTechnicalAnchor = '' !== trim((string) $menu->dllName)
                || '' !== trim((string) $menu->command)
                || '' !== trim((string) $menu->code_name);

            self::assertFalse($hasTechnicalAnchor, "Unrouted active menu {$menu->menuid} has a technical anchor");

            $remaining[] = $menu->menuid;
        }

        self::assertCount(57, $remaining);
    }
}
