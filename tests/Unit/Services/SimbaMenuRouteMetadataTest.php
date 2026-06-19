<?php

declare(strict_types=1);

namespace Diepxuan\Catalog\Tests\Unit\Services;

use Diepxuan\Catalog\Services\SimbaMenuRouteMetadata;
use Diepxuan\Simba\Models\SysMenu;
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

    public function testGroupMenuWithReportFlagAndCallableMetadataGetsReportRoute(): void
    {
        $metadata = $this->metadata([
            $this->menu('02.80.02', SysMenu::TYPE_GROUP, 'GL', 'F5GLRptTH01', report: true),
        ]);

        self::assertSame(SimbaMenuRouteMetadata::TYPE_REPORT, $metadata->routes()['gl.rpt.f5glrptth01']['source_type']);
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
            $this->menu('90.10.29', SysMenu::TYPE_UTILITY, 'CA', 'SIDMTGNT'),
        ]);

        $routes = $metadata->routes();

        self::assertSame(SimbaMenuRouteMetadata::TYPE_DICTIONARY, $routes['so.dict.ardmkh']['source_type']);
        self::assertSame(SimbaMenuRouteMetadata::TYPE_REPORT, $routes['gl.rpt.glrptctgs01']['source_type']);
        self::assertSame(SimbaMenuRouteMetadata::TYPE_VOUCHER, $routes['po.vch.povchpo3']['source_type']);
        self::assertSame(SimbaMenuRouteMetadata::TYPE_CUSTOM, $routes['ca.proc.sidmtgnt']['source_type']);
    }

    public function testReportFlagClassifiesAsReport(): void
    {
        $metadata = $this->metadata([
            $this->menu('02.20.11', SysMenu::TYPE_UTILITY, 'GL', 'GLRptCTGS01', report: true),
        ]);

        self::assertSame(SimbaMenuRouteMetadata::TYPE_REPORT, $metadata->routes()['gl.rpt.glrptctgs01']['source_type']);
    }

    public function testRouteNameUsesMenuModuleNotDllPrefix(): void
    {
        $metadata = $this->metadata([
            $this->menu('06.90.02', SysMenu::TYPE_MASTER, 'SO', 'ARDMKH'),
        ]);

        self::assertArrayHasKey('so.dict.ardmkh', $metadata->routes());
        self::assertArrayNotHasKey('ar.dict.ardmkh', $metadata->routes());
    }

    public function testDuplicateRouteNameAppendsMenuId(): void
    {
        $metadata = $this->metadata([
            $this->menu('02.20.11', SysMenu::TYPE_REPORT, 'GL', 'DynamicReport'),
            $this->menu('02.20.14', SysMenu::TYPE_REPORT, 'GL', 'DynamicReport'),
        ]);

        $routes = $metadata->routes();

        self::assertArrayHasKey('gl.rpt.dynamicreport', $routes);
        self::assertArrayHasKey('gl.rpt.dynamicreport-02-20-14', $routes);
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
