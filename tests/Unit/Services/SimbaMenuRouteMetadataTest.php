<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Tests\Unit\Services;

use Diepxuan\Catalog\Services\SimbaMenuRouteMetadata;
use Diepxuan\Catalog\Models\Simba\SysMenu;
use PHPUnit\Framework\TestCase;

final class SimbaMenuRouteMetadataTest extends TestCase
{
    public function testSkipsRootAndEmptyGroupMenus(): void
    {
        $metadata = $this->metadata([
            $this->menu('02.00.00', SysMenu::TYPE_MODULE_ROOT, 'GL', ''),
            $this->menu('02.10.00', SysMenu::TYPE_GROUP, 'GL', ''),
        ]);

        self::assertSame([], $metadata->routes());
    }

    public function testReportHeaderWithoutCallableMetadataIsSkipped(): void
    {
        $metadata = $this->metadata([
            $this->menu('02.25.00', SysMenu::TYPE_REPORT, 'GL', '', report: true),
        ]);

        self::assertSame([], $metadata->routes());
    }

    public function testClassifiesMenuTypes(): void
    {
        $metadata = $this->metadata([
            $this->menu('06.90.02', SysMenu::TYPE_MASTER, 'SO', 'ARDMKH'),
            $this->menu('02.20.11', SysMenu::TYPE_REPORT, 'GL', 'GLRptCTGS01'),
            $this->menu('10.10.14', SysMenu::TYPE_VOUCHER, 'PO', 'POVchPO3', codeName: 'PO3'),
            $this->menu('90.10.29', SysMenu::TYPE_SETUP, 'CA', 'SIDMTGNT'),
        ]);

        $routes = $metadata->routes();

        self::assertSame(SimbaMenuRouteMetadata::TYPE_DICTIONARY, $routes['so.dict.ardmkh']['source_type']);
        self::assertSame(SimbaMenuRouteMetadata::TYPE_REPORT, $routes['gl.rpt.glrptctgs01']['source_type']);
        self::assertSame(SimbaMenuRouteMetadata::TYPE_VOUCHER, $routes['po.vch.povchpo3']['source_type']);
        self::assertSame(SimbaMenuRouteMetadata::TYPE_CUSTOM, $routes['ca.proc.sidmtgnt']['source_type']);
    }

    public function testGroupMenuWithReportFlagIsSkippedBySourceRouteRules(): void
    {
        $metadata = $this->metadata([
            $this->menu('02.80.02', SysMenu::TYPE_GROUP, 'GL', 'F5GLRptTH01', report: true),
        ]);

        self::assertSame([], $metadata->routes());
    }

    public function testRouteNameUsesMenuModuleNotDllPrefix(): void
    {
        $metadata = $this->metadata([
            $this->menu('06.90.02', SysMenu::TYPE_MASTER, 'SO', 'ARDMKH'),
        ]);

        self::assertArrayHasKey('so.dict.ardmkh', $metadata->routes());
        self::assertArrayNotHasKey('ar.dict.ardmkh', $metadata->routes());
    }

    public function testRouteNameFormatSlugAndNoDuplicates(): void
    {
        $metadata = $this->metadata([
            $this->menu('02.90.02', SysMenu::TYPE_MASTER, 'GL', 'GLDMTK', codeName: 'TK'),
            $this->menu('10.10.14', SysMenu::TYPE_VOUCHER, 'PO', '', codeName: 'PO3'),
            $this->menu('02.20.11', SysMenu::TYPE_REPORT, 'GL', 'GLRptCTGS01'),
        ]);

        $routes = $metadata->routes();
        self::assertSame(['gl.dict.gldmtk', 'po.vch.po3', 'gl.rpt.glrptctgs01'], array_keys($routes));
        foreach (array_keys($routes) as $routeName) {
            self::assertMatchesRegularExpression('/^[a-z0-9-]+\\.(vch|dict|rpt|proc)\\.[a-z0-9-]+$/', $routeName);
        }
        self::assertCount(count(array_unique(array_keys($routes))), $routes);
    }

    public function testRouteNameSuffixAppendsCompactMenuId(): void
    {
        $metadata = $this->metadata([
            $this->menu('10.30.11', SysMenu::TYPE_REPORT, 'PO', 'ARRptBCCN01'),
            $this->menu('10.30.23', SysMenu::TYPE_REPORT, 'PO', 'ARRptBCCN01'),
        ]);

        $routes = $metadata->routes();

        self::assertSame(['po.rpt.arrptbccn01', 'po.rpt.arrptbccn01103023'], array_keys($routes));
        self::assertArrayNotHasKey('po.rpt.arrptbccn01-10-30-23', $routes);
    }

    public function testCatalogZsysmenuUsesSysMenuContract(): void
    {
        self::assertTrue(is_subclass_of(
            \Diepxuan\Catalog\Models\Simba\Zsysmenu::class,
            SysMenu::class,
        ));

        self::assertTrue(method_exists(
            \Diepxuan\Catalog\Models\Simba\Zsysmenu::class,
            'getDisplayName',
        ));
    }

    public function testCatalogZsysmenuCanPassSysMenuTypedClosures(): void
    {
        $zmenu = (new \ReflectionClass(\Diepxuan\Catalog\Models\Simba\Zsysmenu::class))->newInstanceWithoutConstructor();
        $zmenu->setRawAttributes([
            'menuid' => '99.99.99',
            'bar' => 'Z Menu',
            'short_name' => '',
            'dllName' => 'ZDLL',
            'code_name' => '',
        ], true);

        $filter = static fn (SysMenu $menu): bool => '' !== trim((string) $menu->menuid);

        self::assertTrue($filter($zmenu));
        self::assertSame('Z Menu', $zmenu->getDisplayName());
    }

    /**
     * @param list<SysMenu> $menus
     */
    private function metadata(array $menus): SimbaMenuRouteMetadata
    {
        return new SimbaMenuRouteMetadata(testMenus: $menus);
    }

    private function menu(string $menuid, string $type, string $module, string $dllName, string $codeName = '', bool $report = false): SysMenu
    {
        $menu = (new \ReflectionClass(SysMenu::class))->newInstanceWithoutConstructor();
        $menu->setRawAttributes([
            'menuid' => $menuid,
            'type' => $type,
            'moduleid' => $module,
            'dllName' => $dllName,
            'command' => '',
            'code_name' => $codeName,
            'bar' => $dllName,
            'short_name' => $dllName,
            'report' => $report,
            'active' => true,
        ], true);

        return $menu;
    }
}
